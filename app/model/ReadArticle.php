<?php

require_once __DIR__ . '/InitPDO.php';

class ReadArticle
{
    private InitPdo $pdo;

    /**
     * @throws Exception
     */
    public function __construct($pdo)
    {
        if ($pdo == null)
            throw new Exception("Pdo not set", 1);
        $this->pdo = $pdo;
    }

    /**
     * @param $id L'id de l'article.
     * @return Array retourne les informations sur l'article en question.
     */
    public function retrieveArticle($id): array
    {
        $sql = '
            SELECT articles.id article_id, parution_date, views, id_image_pres, title, content, users.id user_id, users.firstname, users.lastname
            FROM articles
            JOIN users ON articles.created_by = users.id
            WHERE articles.id = :id
        ';

        try {
            $req = $this->pdo->executeQuery($sql, [':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Impossible de récupèrer l'article", 2);
            return [];
        }

        $parutionDate = $req[0]['parution_date'];
        $views = $req[0]['views'];
        $id_image_pres = IMAGE_MANAGER->getImageArticleFromId($req[0]['id_image_pres']);
        $title = $req[0]['title'];
        $content = $req[0]['content'];
        $creatorFirstname = $req[0]['firstname'];
        $creatorLastname = $req[0]['lastname'];
        $userCanEdit = ($req[0]['user_id'] == $_SESSION['user-id'] || $_SESSION['user-role'] == 'admin');

        return [
            'parution_date' => $parutionDate,
            'views' => $views,
            'image_pres' => $id_image_pres,
            'title' => $title,
            'content' => $content,
            'creator_firstname' => $creatorFirstname,
            'creator_lastname' => $creatorLastname,
            'user-can-edit' => $userCanEdit,
            'article-id' => $req[0]['article_id'],
        ];
    }

    public function updateArticle($id, $title, $content): void
    {
        $sql = '
            UPDATE articles
            SET title = :title, content = :content
            WHERE id = :id
        ';

        try {
            $this->pdo->executeQuery($sql, [
                ':title' => $title,
                ':content' => $content,
                ':id' => $id,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Impossible de modifier l'article", 2);
        }
    }

    public function createEmptyArticle()
    {
        $sql = '
            INSERT INTO articles (parution_date, id_image_pres, title, description, content, created_by)
            VALUES (NOW(), 1, "Titre de l\'article", "Description de l\'article", "Contenu de l\'article", :created_by)
        ';

        $this->pdo->executeQuery($sql, [
            ':created_by' => $_SESSION['user-id']
        ]);

        return $this->pdo->getLastInsertId();
    }
}
