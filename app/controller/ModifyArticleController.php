<?php

/**
 * @var IMAGE_MANAGER ImageManager
 */

require_once __DIR__ . '/../model/ReadArticle.php';

require_once __DIR__ . '/../utils/utils.php';

require_once __DIR__ . '/../config/htmlpurifier.php';

class ModifyArticleController
{
    private HtmlPurifierConfig $purifier;

    public function __construct()
    {
        $this->purifier = new HTMLPurifierConfig();
    }

    public function handleModifyArticle()
    {
        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user-id'])) {
            addNotification("error", "Vous devez être connecté pour modifier un article.");
            header('location: /login');
            exit();
        }

        // On vérifie que l'utilisateur a le droit de modifier un article ( seul un role creator ou admin le peut le faire )
        if (!isset($_SESSION['user-role']) || ($_SESSION['user-role'] != 'creator' && $_SESSION['user-role'] != 'admin')) {
            addNotification("error", "Vous n'avez pas le droit de modifier un article.");
            header('location: /');
            exit();
        }


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

            $description = htmlspecialchars(trim($_POST['description'] ?? ''));

            $content = $_POST['content'] ?? '';

            $content = $this->purifier->purify($content);

            if (!$title || !$content) {
                addNotification("error", "Titre ou contenu invalide !");
                header('location: /modify-article?id=' . $id);
                exit();
            }

            try {
                // On modifie le contenu de l'article
                $model = new ReadArticle(PDO);
                $model->updateArticle($id, $title, $description, $content);

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

    public function handleCreateArticle()
    {
        // On vérifie la connexion
        if (isset($_SESSION['user-id'])) {
            // Vérification des droits pour la création d'article ( seul un role creator ou admin le peut le faire )
            if (isset($_SESSION['user-role']) && ($_SESSION['user-role'] == 'creator' || $_SESSION['user-role'] == 'admin')) {
                // On créer dans la base de données un article vide
                $model = new ReadArticle(PDO);
                $id = $model->createEmptyArticle($_SESSION['user-id']);

                // On va sur la page de modification d'article
                header('location: /modify-article?id=' . $id);
            } else {
                // Si l'utilisateur n'a pas le droit de créer un article, on le redirige vers la page d'accueil
                addNotification("error", "Vous n'avez pas le droit de créer un article.");
                header('location: /');
            }
        } else {
            // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
            addNotification("error", "Vous devez être connecté pour créer un article.");
            header('location: /login');
        }
    }
}
