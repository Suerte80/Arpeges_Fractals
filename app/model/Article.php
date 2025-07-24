<?php

require_once __DIR__ . '/InitPDO.php';

/**
 * Model pour gérer les articles.
 */
class Article{
    private InitPDO $pdo;

    /**
     * Constructeur de la classe Article.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     */
    public function __construct(InitPDO $pdo) {
        if( !$pdo ){
            throw new Exception("PDO instance is required for Article model.");
        } else {
            $this->pdo = $pdo;
        }
    }

    public function getAll()
    {
        return $this->pdo->executeQuery(
            "SELECT a.id, a.parution_date, a.views, a.title, a.description, a.content, i.image_filepath
             FROM articles a
             JOIN images i ON a.id_image_pres = i.id
             ORDER BY a.parution_date DESC;"
        );
    }
}