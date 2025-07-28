<?php

require_once __DIR__ . '/InitPDO.php';

class Login{

    private InitPdo $pdo;

    public function __construct(InitPDO $pdo)
    {
        if (!$pdo) {
            throw new Exception("PDO instance is required for Signup model.");
        } else {
            $this->pdo = $pdo;
        }
    }

    public function login($email, $password)
    {
        // On veut récupérer l'id, le mail, l'username et le password quand pour les email (UNIQUE) et password corresponde.
        $sql = '
            SELECT id, email, username, password, role
            FROM users
            WHERE email=:email;
        ';

        $resReq = $this->pdo->executeQuery($sql, array(':email' => $email));

        error_log("FetchAll: " . print_r($resReq, true));

        // On vérifie qu'il existe au moins une ligne ( normalement qu'une ligne vu que le champ email est unique )
        if( count($resReq) > 0 ){

            $resReq = $resReq[0];

            // On vérifie le password avec le hash de la bdd.
            if(password_verify($password, $resReq['password'])){
                // On enregistre les différents champs dans une session.
                $_SESSION['user-id'] = $resReq['id'];
                $_SESSION['user-is-connected'] = true;
                $_SESSION['user-email'] = $resReq['email'];
                $_SESSION['user-username'] = $resReq['username'];
                $_SESSION['user-role'] = $resReq['role'];

                error_log("Connexion réussis?");
                return true;
            }

            error_log("Mot de passe incorrecte?");
            return false;
        } else{
            error_log("Email ou mot de passe incorrect !");
            return false;
        }
    }

    public function logout()
    {
        session_destroy();
    }

}