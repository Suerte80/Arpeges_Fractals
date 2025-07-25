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

    public function login($email, $hashedPassword)
    {
        // On veut récupérer l'id, le mail, l'username et le password quand pour les email (UNIQUE) et password corresponde.
        $sql = '
            SELECT id, email, username, password, role
            FROM users
            WHERE email=:email AND password=:hashedPassword;
        ';

        // ON execute la requête
        $resReq = $this->pdo->executeQuery(
            $sql,
            [
                ':email' => $email,
                ':hashedPassword' => $hashedPassword
            ]
        );

        if( count($resReq) > 0 ){

            $_SESSION['user-id'] = $resReq['id'];
            $_SESSION['user-is-connected'] = true;
            $_SESSION['user-email'] = $resReq['email'];
            $_SESSION['user-username'] = $resReq['username'];
            $_SESSION['user-role'] = $resReq['role'];

        } else{
            echo "Email ou mot de passe incorrect !";
        }
    }

}