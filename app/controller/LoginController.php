<?php

require_once __DIR__ . '/../model/Login.php';

require_once __DIR__ . '/../utils/utils.php';

class LoginController{

    // fonction qui valide les données : https://www.pierre-giraud.com/php-mysql-apprendre-coder-cours/securiser-valider-formulaire/
    
    public function handleLogin()
    {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            $email = valid_donnees($_POST['mail']);
            $password = valid_donnees($_POST['password']);

            $errors = [];

            if(empty($email)) $errors[] = "Email Vide";
            if(empty($password)) $errors[] = "Password Vide";

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";

            if(empty($errors)){
                $login = new Login(PDO);

                if( $login->login($email, $password) ){

                    addNotification("info", "Connexion réussis !");
                    header('Location: /');
                    exit();

                } else{
                    addNotification("warn", "Login ou mot de passe incorrect !");
                    error_log("Echec de la méthode login();");
                }
            } else{
                addNotification("warn", "Login ou mot de passe incorrect !");
            }
        } else{
            include_once __DIR__ . '/../view/pages/login.php';
        }
    }

}