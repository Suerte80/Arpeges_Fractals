<?php

require_once __DIR__ . '/../model/ReadArticle.php';

require_once __DIR__ . '/../utils/utils.php';

class ReadArticleController
{
    public function handleReadArticle()
    {
        if( $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) ){
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if(!$id){
                addNotification("error", "Identifiant d'article invalide !");
                header('location: /');
                exit();
            }

            try{
                $model = new ReadArticle(PDO);

                $resArticle = $model->retrieveArticle($_GET['id']);
            } catch (Exception $e) {


                switch ($e->getCode()) {
                    case 1:
                        addNotification("error", $e->getMessage());
                        header('location: /');
                        exit();
                    case 2:
                        addNotification("error", $e->getMessage());
                        header("Location: /");
                        exit();
                    default:
                        break;
                }
            }

            include(__DIR__ . '/../view/pages/readArticle.php');
        } else{
            addNotification("error", "Article introuvable");
            header("Location: /");
        }
    }
}