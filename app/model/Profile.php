<?php

require_once __DIR__ . '/../utils/ImageManager.php';

class Profile
{
    private InitPdo $pdo;

    public function __construct($pdo)
    {
        if(!$pdo)
            throw new Exception("PDO instance is required for Profile model.");
        else
            $this->pdo = $pdo;
    }

    public function handleRetrieveProfile() : Array
    {
        // On vérifie que l'utilisateur est connecté
        if( !isset($_SESSION['user-id']) ){
            return ['error' => "Utilisateur non connecté."];
        }

        $sql = '
            SELECT id, firstname, lastname, email, username, id_avatar
            FROM users
            WHERE id=:id
        ';

        $resReq = $this->pdo->executeQuery($sql, ['id' => $_SESSION['user-id']]);

        error_log(print_r($resReq, true));

        // On récupère le filepath de l'image et son alt via le manager d'image.
        $imageManager = IMAGE_MANAGER;
        $image = $imageManager->getImageAvatarFromId($resReq[0]['id_avatar']);

        return [
            'firstname' => $resReq[0]['firstname'],
            'lastname' => $resReq[0]['lastname'],
            'email' => $resReq[0]['email'],
            'username' => $resReq[0]['username'],
            'image_filepath' => $image['filepath'],
            'image_alt' => $image['alt'],
        ];
    }

    public function handleModifyProfile($id, $firstname, $lastname, $username, $email)
    {
        $sql = '
            UPDATE users
            SET firstname=:firstname,
                lastname=:lastname,
                username=:username,
                email=:email
            WHERE id=:id;
        ';

        $sqlWithoutEmail = '
            UPDATE users
            SET firstname=:firstname,
                lastname=:lastname,
                username=:username
            WHERE id=:id;
        ';

        $sqlVerifyUniqueEmail = '
            SELECT id
            FROM users
            WHERE email=:email;
        ';

        // On vérifie que l'email est unique dans la base de données.
        if( $_SESSION['user-email'] !== $email ){
            if( count(
                    $this->pdo->executeQuery($sqlVerifyUniqueEmail,[':email' => $email])
                ) != 0 ){
                throw new Exception("L'adresse email existe déjà !", 1);
            }
        }

        // modification du profile dans la bdd.
        $resReq = $this->pdo->executeQuery($sql,
            [
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':username' => $username,
                ':email' => $email,
                ':id' => $id,
            ]
        );

        addNotification("info", "Modification du profile effectué.");
        header('location: /profile');
    }
}