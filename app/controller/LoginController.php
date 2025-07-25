<?php

require_once __DIR__ . '../model/Login.php';

class LoginController{

    // fonction qui valide les données : https://www.pierre-giraud.com/php-mysql-apprendre-coder-cours/securiser-valider-formulaire/
    private function valid_donnees($donnees){
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
        return $donnees;
    }
    
    public function handleLogin()
    {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            $email = $this->valid_donnees($_POST['email']);
            $password = $this->valid_donnees($_POST['password']);

            $erreur = [];

            if(empty($email)) $erreur[] = "Email Vide";
            if(empty($password)) $errreur[] = "Password Vide";

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreur[] = "Email invalide";

            if(empty($erreur)){
                $login = new Login(PDO);

                if( $login->login($email, password_hash($password, PASSWORD_DEFAULT)) ){

                    header('Location: /');
                    exit();

                } else{
                    error_log("Echec de la méthode login();");
                }
            }
        }
    }

}