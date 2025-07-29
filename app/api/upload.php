<?php

/* TODO Gèrer les erreur */
// TODO ajouter un alt

$allowedMimeTypes = [
    'image/jpeg',
    'image/png'
];

$storageAvatar = "/../../public/avatars/";

// On vérifie que la méthode est en poste
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // On vérifie que la session est bien ouverte et l'utilisateur est connecté.
    if(isset($_SESSION['user-is-connected']) && isset($_POST['profile-image-update'])){

        // ON vérifie que le fichier a bien été uploadé et qu'il n'y a pas d'erreur
        if( isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK ){

            // Vérification de type de fichier s'il est présent dans la liste
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['image']['tmp_name']);
            $ext = strtolower($mimeType);
            if(!in_array($ext, $allowedMimeTypes)){
                addNotification("error", "Fichier non pris en charge");
                exit();
            }

            // Récupération du nom de fichier temporaire
            $tmp = $_FILES['image']['tmp_name'];
            // Récupèration de l'extension du fichier
            $extention = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            // Nommae aléatoire
            $randomName = bin2hex(random_bytes(16)) . '.' . $extention;
            // création de chemin du fichier pour la copie.
            $destination = __DIR__ . $storageAvatar . $randomName;

            // On vérifie que le déplacement s'est bien passé
            if(move_uploaded_file($tmp, $destination)){

                $basename = basename($destination);

                $sql = '
                    INSERT INTO images_avatar(image_filepath, alt)
                    VALUES (:image_filepath, :alt)
                ';

                $sqlUpdateProfile = '
                    UPDATE users
                    JOIN images_avatar i ON i.image_filepath = :image_filepath
                    SET users.id_avatar = i.id
                    WHERE users.id = :id;
                ';

                $query = PDO->executeQuery($sql, [
                    ':image_filepath' => $basename,
                    ':alt' => "Image de profil de " . htmlspecialchars($_SESSION['user-username'])
                ]);

                $queryUpdateAvatar = PDO->executeQuery($sqlUpdateProfile, [
                    ':id' => $_SESSION['user-id'],
                    ':image_filepath' => $basename
                ]);

                addNotification("info", "Upload de l'image fait !");
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => "Upload de l'image fait !",
                    'filename' => '/uploads/' . $basename,
                ]);
            } else{
                addNotification("error", "Impossible upload");
                echo json_encode(['success' => false, 'message' => "Fichier manquant"]);
                exit();
            }

        } else{
            addNotification("error", "Impossible upload");
            echo json_encode(['success' => false, 'message' => "Accès refusé"]);
            exit();
        }
    } else{
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => "Méthode non autorisée"]);
    }

}