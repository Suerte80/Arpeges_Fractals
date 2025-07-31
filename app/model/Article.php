<?php

require_once __DIR__ . '/InitPDO.php';

/**
 * Model pour gérer les articles.
 */
class Article
{
    private InitPdo $pdo;

    /**
     * Constructeur de la classe Article.
     * Initialise la connexion à la base de données.
     *
     * @param InitPDO $pdo Instance de PDO pour la connexion à la base de données.
     */
    public function __construct(InitPdo $pdo)
    {
        if (!$pdo) {
            throw new Exception("PDO instance is required for Article model.");
        } else {
            $this->pdo = $pdo;
        }
    }

    public function getAll()
    {
        $arr = $this->pdo->executeQuery(
            "   SELECT a.id, a.parution_date, a.views, a.title, a.description, a.content, i.image_filepath
                FROM articles a
                JOIN images i ON a.id_image_pres = i.id
                ORDER BY a.parution_date DESC;"
        );

        if (isset($arr) && is_array($arr) && count($arr) > 0) {
            foreach ($arr as $key => $article) {
                // Convertir la date de parution en format lisible
                $arr[$key]['parution_date'] = date('d/m/Y', strtotime($article['parution_date']));
                // Ajouter le chemin complet de l'image


                // TODO Enlever cette condition lorsqu'on aura changer les valeurs dans la bdd
                if (
                    strcmp($article['image_filepath'], "images/articles/article.png") === 0
                    ||  strcmp($article['image_filepath'], "images/default.png") === 0
                ) {
                    $arr[$key]['image_filepath'] = $article['image_filepath'];
                } else {
                    $arr[$key]['image_filepath'] = ImageManager::PUBLIC_STORAGE_ARTICLE . $article['image_filepath'];
                }
            }
        }

        return $arr;
    }
}
