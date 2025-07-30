<?php

/**
 * @var IMAGE_MANAGER ImageManager
 */

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
                addNotification("error", $e->getMessage());
                header("Location: /");
                exit();
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

            try {
                // On modifie le contenu de l'article
                $model = new ReadArticle(PDO);
                $model->updateArticle($id, $title, $content);

                // On modifie l'image si elle a été envoyée
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    IMAGE_MANAGER->uploadImage(StorageType::article, $_POST['article-id']);
                }

                // Affichage de la notification
                addNotification("success", "Article modifié avec succès !");
                header('location: /article?id=' . $id);
            } catch (Exception $e) {
                addNotification("error", $e->getMessage());
                header('location: /modify-article?id=' . $id);
                exit();
            }
        } else {
            addNotification("error", "Article introuvable");
            header("Location: /");
        }
    }
}
