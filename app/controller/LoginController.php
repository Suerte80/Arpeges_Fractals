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

                try{
                    $login->login($email, $password);
                } catch (Exception $e){
                    $errCode = $e->getCode();
                    $errMsg = $e->getMessage();

                    switch ($errCode){
                        case 1:
                            addNotification("warn", $errMsg);
                            header('Location: /login');
                            return;
                        case 2:
                            addNotification("warn", $errMsg);
                            header('Location: /login');
                            return;
                    }
                }

                // Si tous c'est bien passé
                addNotification('info', "Connexion réussie !");
                header('Location: /');

            } else{
                error_log("3");

                addNotification("warn", "Les informations fournis sont non conforme !");
                header('Location: /login');
            }
        } else{
            include_once __DIR__ . '/../view/pages/login.php';
        }
    }

}