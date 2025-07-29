<?php

require_once __DIR__ . '/../model/Profile.php';

require_once __DIR__ . '/../utils/utils.php';

class ProfileController
{
    function handleProfile()
    {
        $profileModel = new Profile(PDO);

        if(!isset($_SESSION['user-is-connected'])){
            addNotification('error', 'Vous n\'êtes pas connécté !');
            header('location: /');
            exit();
        }

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            // Récupèration et vérification des entrées utilisateurs
            if(
                valid_donnees($_POST['firstname'])
                && valid_donnees($_POST['lastname'])
                && valid_donnees($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
                && valid_donnees($_POST['username'])
            )
            {
                $profileModel->handleModifyProfile($_SESSION['user-id'], $_POST['firstname'], $_POST['lastname'], $_POST['username'], $_POST['email']);
            }
        } else
        {
            // On récupère ici les informations de l'utilisateurs.
            $userInfo = $profileModel->handleRetrieveProfile();
            error_log(print_r($userInfo, true));
            if( isset($userInfo['error']) || count($userInfo) <= 0 ){
                addNotification('error', $userInfo['error']);
                header('Location: /');
            } else{
                include_once __DIR__ . '/../view/pages/profile.php';
            }
        }
    }
}