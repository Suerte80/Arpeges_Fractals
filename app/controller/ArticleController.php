<?php

require_once '../model/Article.php';

/**
 * Controller pour gérer les articles.
 */
class ArticleController {
    
    /**
     * Affiche tous les articles au format JSON.
     */
    public function getArticleJSON(){
        $model = new Article();
        $articles = $model->getAll();

        header('Content-Type: application/json');
        if ($articles) {
            echo json_encode($articles);
        } else {
            echo json_encode([]);
        } 
    }
}