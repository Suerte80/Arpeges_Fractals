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
        if($pdo == null)
            throw new Exception("Pdo not set", 1);
        $this->pdo = $pdo;
    }

    /**
     * @param $id L'id de l'article.
     * @return Array retourne les informations sur l'article en question.
     */
    public function retrieveArticle($id): Array
    {
        $sql = '
            SELECT parution_date, views, id_image_pres, title, content, users.firstname, users.lastname
            FROM articles
            JOIN users ON articles.created_by = users.id
            WHERE articles.id = :id
        ';

        try{
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

        return [
            'parution_date' => $parutionDate,
            'views' => $views,
            'image_pres' => $id_image_pres,
            'title' => $title,
            'content' => $content,
            'creator_firstname' => $creatorFirstname,
            'creator_lastname' => $creatorLastname,
        ];
    }
}