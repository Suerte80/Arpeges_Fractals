<?php

require_once __DIR__ . '/../model/Profile.php';

class ProfileController
{
    function handleProfile()
    {
        $profileModel = new Profile(PDO);

        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            // TODO
            // On modifie ici le profile en vérifiant les entrées utilisateurs.
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