<?php

require_once __DIR__ . '/../model/ReadArticle.php';

require_once __DIR__ . '/../utils/utils.php';

class ModifyArticleController
{
    public function handleModifyArticle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                addNotification("error", "Identifiant d'article invalide !");
                header('location: /');
                exit();
            }

            try {
                $model = new ReadArticle(PDO);
                $resArticle = $model->retrieveArticle($id);
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

            include(__DIR__ . '/../view/pages/modifyArticle.php');
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article-id'])) {
            $id = filter_input(INPUT_POST, 'article-id', FILTER_VALIDATE_INT);
            if (!$id) {
                addNotification("error", "Identifiant d'article invalide !");
                header('location: /');
                exit();
            }

            $title = htmlspecialchars(trim($_POST['title'] ?? ''));

            $allowedTags = '<p><br><strong><em><ul><ol><li><blockquote><a><h2><h3>';
            $content = trim(strip_tags($_POST['content'] ?? '', $allowedTags));

            if (!$title || !$content) {
                addNotification("error", "Titre ou contenu invalide !");
                header('location: /modify-article?id=' . $id);
                exit();
            }

            // Vérification de l'image a modifié
            $check = getimagesize($_FILE['image']['tmp_name'] ?? null);
            if ($check) {
            } else {
                $check = null;
            }

            try {
                $model = new ReadArticle(PDO);
                $model->updateArticle($id, $title, $content);
                addNotification("success", "Article modifié avec succès !");
                header('location: /article?id=' . $id);
            } catch (Exception $e) {
                addNotification("error", $e->getMessage());
                header('location: /modify-article?id=' . $id);
            }
        } else {
            addNotification("error", "Article introuvable");
            header("Location: /");
        }
    }
}
