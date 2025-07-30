<?php

require_once __DIR__ . '/../model/AdminPanel.php';

require_once __DIR__ . '/../utils/utils.php';

class AdminPanelController
{
    public function handleAdminPanel()
    {
        // On vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['user-id'])) {
            addNotification("error", "Vous devez être connecté pour accéder au panneau d'administration.");
            header('location: /login');
            exit();
        }

        // On vérifie que l'utilisateur a le droit d'accéder au panneau d'administration (seul un role admin le peut)
        if (!isset($_SESSION['user-role']) || $_SESSION['user-role'] != 'admin') {
            addNotification("error", "Vous n'avez pas le droit d'accéder au panneau d'administration.");
            header('location: /');
            exit();
        }

        try {
            $model = new AdminPanel(PDO);
            $users = $model->getAllUsers();
        } catch (Exception $e) {
            addNotification("error", $e->getMessage());
            header("Location: /");
            exit();
        }

        include(__DIR__ . '/../view/pages/adminPanel.php');
    }

    public function handleUpdateUserRole()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user-id']) && isset($_POST['role'])) {
            $userId = filter_input(INPUT_POST, 'user-id', FILTER_VALIDATE_INT);
            $role = htmlspecialchars(trim($_POST['role'] ?? ''));

            if (!$userId || empty($role)) {
                addNotification("error", "Identifiant d'utilisateur ou rôle invalide !");
                header('location: /admin/user/panel');
                exit();
            }

            try {
                $model = new AdminPanel(PDO);
                $model->updateUserRole($userId, $role);
                addNotification("success", "Rôle de l'utilisateur mis à jour avec succès.");
            } catch (Exception $e) {
                addNotification("error", $e->getMessage());
            }

            header('location: /admin/user/panel');
        } else {
            addNotification("error", "Requête invalide.");
            header('location: /admin/user/panel');
        }
    }

    public function handleDeleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user-id'])) {
            $userId = filter_input(INPUT_POST, 'user-id', FILTER_VALIDATE_INT);

            if (!$userId) {
                addNotification("error", "Identifiant d'utilisateur invalide !");
                header('location: /admin/user/panel');
                exit();
            }

            try {
                $model = new AdminPanel(PDO);
                $model->deleteUser($userId);
                addNotification("success", "Utilisateur supprimé avec succès.");
            } catch (Exception $e) {
                addNotification("error", $e->getMessage());
            }

            header('location: /admin/user/panel');
        } else {
            addNotification("error", "Requête invalide.");
            header('location: /admin/user/panel');
        }
    }
}
