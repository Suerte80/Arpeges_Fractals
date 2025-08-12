<?php

/* TODO Gèrer les erreur */
// TODO ajouter un alt

/*
 * @var IMAGE_MANAGER ImageManager
 */

$allowedMimeTypes = [
    'image/jpeg',
    'image/png'
];

$storageAvatar = "/../../public/avatars/";
$storageArticle = "/../../public/article_image/";

// On vérifie que la méthode est en poste
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // On vérifie que la session est bien ouverte et l'utilisateur est connecté.
    if (isset($_SESSION['user-is-connected'])) {

        // On vérifie qu'on a bien le message profile-image-update dans le POST
        // C'est pour mettre a jours l'image de profil de l'utilisateur.
        if (isset($_POST['profile-image-update'])) {
            try {
                // On récupère l'image de l'avatar de l'utilisateur.
                $ret = IMAGE_MANAGER->uploadImage(StorageType::avatar, $_SESSION['user-id']);

                // On envoie la notification et la réponse JSON
                addNotification("info", "Upload de l'image fait !");
                header('Content-Type: application/json');

                // Mise a jour du chemin de l'image de profile 
                $_SESSION['user-profil-image'] = $ret['filename'];

                echo json_encode([
                    'success' => true,
                    'message' => "Upload de l'image fait !",
                    'filename' => $ret['filename'],
                ]);
            } catch (Exception $e) {
                // En cas d'erreur on envoi le message d'erreur.
                $message = $e->getMessage();

                http_response_code(405);
                echo json_encode(['success' => false, 'message' => $message]);
            }
        } else if (isset($_POST['article-image-content'])) {
            // On vérifie qu'on a bien le message article-image-content dans le POST
            // C'est pour ajouter des images dans l'articles.
            try {
                // On récupère l'image de l'article.
                $ret = IMAGE_MANAGER->uploadImageForArticle($_POST['article-id'], $_SESSION['user-id']);

                // On envoie la notification et la réponse JSON
                addNotification("info", "Upload de l'image fait !");
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => "Upload de l'image fait !",
                    'filename' => $ret['filename'],
                ]);
            } catch (Exception $e) {
                // En cas d'erreur on envoi le message d'erreur.
                $message = $e->getMessage();

                http_response_code(405);
                echo json_encode(['success' => false, 'message' => $message]);
            }
        } else {
            // Si on n'a pas le bon message envoyé en POST 
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => "Méthode non autorisée"]);
        }
    } else {
        // Si l'utilisateur n'est pas connecté.
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => "Vous n'êtes pas connecté !"]);
    }
}
