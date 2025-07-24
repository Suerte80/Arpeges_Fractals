<?php

/**
 * Model pour gérer les articles.
 */
class Article{
    private $pdo;

    /**
     * Constructeur de la classe Article.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     */
    public function __construct($pdo = NULL) {
        $this->pdo = $pdo ?? new PDO(
            'mysql:host=mysql;dbname=blog_arpeges_fractals;charset=utf8',
            'root',
            'root',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }

    public function getAll()
    {
        // Prépare et exécute la requête pour récupérer tous les articles
        $stmt = $this->pdo->prepare(
            "SELECT a.id, a.parution_date, a.views, a.title, a.description, a.content, i.image_filepath
             FROM articles a
             JOIN images i ON a.id_image_pres = i.id
             ORDER BY a.parution_date DESC;"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}