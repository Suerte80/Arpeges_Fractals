<?php

require_once __DIR__ . "/../model/Login.php";

class LogoutController{
    public function handleLogout(){
        if( isset($_SESSION["user-is-connected"]) && $_SESSION["user-is-connected"] ){
            $logout = new Login(PDO);

            $logout->logout();

            addNotification("info", "Vous êtes déconnecter");
            header('Location: /');
        } else{
            addNotification("info", "Vous n'êtes pas connecter !");
            header('Location: /');
        }
    }
}