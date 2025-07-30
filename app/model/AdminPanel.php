<?php

require_once __DIR__ . '/InitPDO.php';

class AdminPanel
{
    private InitPDO $pdo;

    public function __construct(InitPDO $pdo)
    {
        if (!$pdo) {
            throw new Exception("PDO instance is required for AdminPanel model.");
        } else {
            $this->pdo = $pdo;
        }
    }

    public function getAllUsers()
    {
        $sql = 'SELECT id, email, username, role FROM users WHERE role != :role';
        $resReq = $this->pdo->executeQuery($sql, [':role' => 'admin']);

        if (count($resReq) > 0) {
            return $resReq;
        } else {
            throw new Exception("Aucun utilisateur trouvé !");
        }
    }

    public function deleteUser($userId)
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $params = array(':id' => $userId);
        $this->pdo->executeQuery($sql, $params);
    }

    public function updateUserRole($userId, $role)
    {
        $sql = 'UPDATE users SET role = :role WHERE id = :id';
        $params = array(':role' => $role, ':id' => $userId);
        $this->pdo->executeQuery($sql, $params);
    }
}
