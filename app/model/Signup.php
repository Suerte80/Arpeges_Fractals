<?php

require_once __DIR__ . '/InitPDO.php';

use App\Service\MailService;

/**
 * Model pour gérer les inscriptions des utilisateurs.
 */
class Signup {
    private InitPDO $pdo;

    /**
     * Constructeur de la classe Signup.
     * Initialise la connexion à la base de données.
     *
     * @param InitPDO $pdo Instance de InitPDO pour la connexion à la base de données.
     */
    public function __construct(InitPDO $pdo) {
        if (!$pdo) {
            throw new Exception("PDO instance is required for Signup model.");
        } else {
            $this->pdo = $pdo;
        }
    }

    /**
     * Fonction pour gérer les inscriptions des utilisateurs peuvent être ajoutées ici.
     * @param firstname Prénom de l'utilisateur.
     * @param lastname Nom de l'utilisateur.
     * @param username Pseudo de l'utilisateur.
     * @param email Email de l'utilisateur.
     * @param password Mot de passe de l'utilisateur non haché.
     */
    public function signup($firstname, $lastname, $username, $email, $password){

        // hashage du password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Query pour l'ajout d'un utilisateur /!\ L'émail doit être vérifié dans le controller
        $sql = "
            INSERT INTO users (firstname, lastname, email, username, password, token_verification) 
            VALUES (:firstname, :lastname, :email, :username, :password, :token_verification);
        ";

        if (!class_exists(\App\Service\MailService::class)) {
            error_log("🚨 MailService introuvable");
        } else {
            error_log("✅ MailService bien chargé");
        }
        
        try{            
            $tokenVerification = bin2hex(openssl_random_pseudo_bytes(25)); // Ici chaque octet donne 2 caractère hex !
            $this->pdo->executeQuery(
                $sql,
                [
                    ":firstname" => $firstname,
                    ":lastname" => $lastname,
                    ":email" => $email,
                    ":username" => $username,
                    ":password" => $password,
                    ":token_verification" => $tokenVerification
                ]
            );
            $mail = new MailService();
            $mail->send('test@dev.local', 'Bienvenue sur Arpèges Fractal !', "<p>Voici votre token: $tokenVerification</p>" );
        } catch(PDOException $e){
            error_log($e->getMessage());
            return false;
        }

        return true;
    }
}