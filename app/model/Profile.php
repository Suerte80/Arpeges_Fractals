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
        $image = $imageManager->getImageFromId($resReq[0]['id_avatar']);

        return [
            'firstname' => $resReq[0]['firstname'],
            'lastname' => $resReq[0]['lastname'],
            'email' => $resReq[0]['email'],
            'username' => $resReq[0]['username'],
            'image_filepath' => $image['filepath'],
            'image_alt' => $image['alt'],
        ];
    }
}