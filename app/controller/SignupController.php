<?php

require_once __DIR__ . '/../model/Signup.php';

/**
 * Controller pour gérer les inscriptions.
 */
class SignupController{

    // fonction qui valide les données : https://www.pierre-giraud.com/php-mysql-apprendre-coder-cours/securiser-valider-formulaire/
    private function valid_donnees($donnees){
        $donnees = trim($donnees);
        $donnees = stripslashes($donnees);
        $donnees = htmlspecialchars($donnees);
        return $donnees;
    }

    /**
     * 
     */
    public function handleSignup()
    {
        error_log($_SERVER['REQUEST_METHOD']);
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            // Onrécupère toutes les données du post.
            $firstname = $this->valid_donnees($_POST['firstname']);
            $lastname = $this->valid_donnees($_POST['lastname']);
            $username = $this->valid_donnees($_POST['username']);
            $email = $this->valid_donnees($_POST['email']);
            $confirmEmail = $this->valid_donnees($_POST['confirm-email']);
            $password = $this->valid_donnees($_POST['password']);
            $confirmPassword = $this->valid_donnees($_POST['confirm-password']);
            
            // Vérification des données
            $erreurs = [];

            // Vérifications de base
            if (empty($firstname)) $erreurs[] = "Le prénom est vide.";
            if (empty($lastname)) $erreurs[] = "Le nom est vide.";
            if (empty($username)) $erreurs[] = "Le nom d'utilisateur est vide.";
            if (empty($email)) $erreurs[] = "L'email est vide.";
            if (empty($confirmEmail)) $erreurs[] = "La confirmation d'email est vide.";
            if (empty($password)) $erreurs[] = "Le mot de passe est vide.";
            if (empty($confirmPassword)) $erreurs[] = "La confirmation du mot de passe est vide.";

            // Correspondance email / password
            if ($email !== $confirmEmail) $erreurs[] = "Les emails ne correspondent pas.";
            if ($password !== $confirmPassword) $erreurs[] = "Les mots de passe ne correspondent pas.";

            // Email valide ?
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = "L'email est invalide.";

            // Si tout est bon
            if (empty($erreurs)) {
                $signup = new Signup(PDO); // ⚠ Remplace `PDO` par ton objet $pdo réel

                error_log("Début de l'inscription...");

                if ($signup->signup($firstname, $lastname, $username, $email, $password)) {
                    addNotification("info", "Utilisateur inscrit avec succès.");
                    header('Location: /');
                } else {
                    error_log("Échec de la méthode signup()");
                }

            } else {
                // Affiche toutes les erreurs dans les logs
                foreach ($erreurs as $erreur) {
                    error_log("❌ " . $erreur);
                }

                // afficher les erreurs côté client
                foreach ($erreurs as $erreur) {
                    addNotification("error", $erreur);
                }

                include_once __DIR__ . '/../view/pages/signup.php';
}
        } else{
            include_once __DIR__ . '/../view/pages/signup.php';
        }
    }
}