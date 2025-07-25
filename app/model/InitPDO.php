<?php

/**
 * Permet d'initialiser la connexion à la base de données PDO.
 * D'executer des requêtes préparé et de retourner les résultats.
 */
class InitPdo{
    private PDO $pdo;

    /**
     * Constructeur de la classe InitPdo.
     * Initialise la connexion à la base de données.
     *
     * @param PDO $pdo Instance de PDO pour la connexion à la base de données.
     */
    public function __construct() {
        $this->pdo = $pdo ?? new PDO(
            'mysql:host=mysql;dbname=blog_arpeges_fractals;charset=utf8',
            'root',
            'root',
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }

    /**
     * Exécute une requête préparée et retourne les résultats.
     *
     * @param string $query La requête SQL à exécuter.
     * @param array $params Les paramètres à lier à la requête.
     * @return array Les résultats de la requête.
     */
    public function executeQuery($query, $params = []):array {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}