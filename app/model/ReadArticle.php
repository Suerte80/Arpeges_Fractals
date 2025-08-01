<?php

require_once __DIR__ . '/InitPDO.php';

class ReadArticle
{
    private InitPdo $pdo;

    /**
     * Constructeur de la classe ReadArticle.
     * @param InitPdo $pdo
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
     * @return Array{
     *  parution_date: string,
     *  views: int,
     *  image_pres: string,
     *  title: string,
     *  description: string,
     *  content: string,
     *  creator_firstname: string|null,
     *  creator_lastname: string|null,
     *  user-can-edit: bool,
     *  article-id: int,
     *  id-playlist-spotify: string
     * @throws Exception S'il y a une erreur lors de la récupération de l'article dans la base de données.
     * }
     */
    public function retrieveArticle($id): array
    {
        // requête SQL pour récupérer les informations de l'article et celle de l'utilisateur qui l'a créé
        // Ici je faisais un inner join mais lorsque l'utilisateur été supprimer ça ne renvoyait pas l'article
        $sql = '
            SELECT 
                articles.id article_id, 
                parution_date, views, 
                id_image_pres, 
                title, 
                description, 
                content, 
                id_playlist_spotify,
                users.id user_id, 
                users.firstname, 
                users.lastname
            FROM articles
            LEFT JOIN users ON articles.created_by = users.id
            WHERE articles.id = :id
        ';

        try {
            // Exécution de la requête avec l'ID de l'article
            $req = $this->pdo->executeQuery($sql, [':id' => $id]);
            error_log("SQL: " . $this->pdo->getLastQuery());
            if (count($req) === 0) {
                // Si aucun article n'est trouvé, on lève une exception
                throw new Exception("Aucun article trouvé avec cet identifiant", 1);
            }
        } catch (PDOException $e) {
            // Si une erreur survient lors de l'éxécution de la requête, on lève une exception
            throw new Exception("Impossible de récupèrer l'article", 2);
            return [];
        }

        // Récupération des différents éléments de l'article
        $parutionDate = $req[0]['parution_date'];
        $views = $req[0]['views'];
        $id_image_pres = IMAGE_MANAGER->getImageArticleFromId($req[0]['id_image_pres']);
        $title = $req[0]['title'];
        $description = $req[0]['description'];
        $content = $req[0]['content'];
        $creatorFirstname = $req[0]['firstname'];
        $creatorLastname = $req[0]['lastname'];
        $userCanEdit = ($req[0]['user_id'] == $_SESSION['user-id'] || $_SESSION['user-role'] == 'admin');
        $idPlaylistSpotify = $req[0]['id_playlist_spotify'] ?? '73gTNzJSyRHlrN1Xg3XgPy';

        return [
            'parution_date' => $parutionDate,
            'views' => $views,
            'image_pres' => $id_image_pres,
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'creator_firstname' => $creatorFirstname,
            'creator_lastname' => $creatorLastname,
            'user-can-edit' => $userCanEdit,
            'article-id' => $req[0]['article_id'],
            'id-playlist-spotify' => $idPlaylistSpotify
        ];
    }

    public function updateArticle($id, $title, $description, $content): void
    {
        $sql = '
            UPDATE articles
            SET title = :title, content = :content, description = :description
            WHERE id = :id
        ';

        try {
            $this->pdo->executeQuery($sql, [
                ':title' => $title,
                ':description' => $description,
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
