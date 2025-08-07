<?php
if (!isset($_SESSION['user-is-connected'])) {
    addNotification('error', 'Vous n\'êtes pas connecté !');
    header('location: /');
}

header('Content-Type: application/json');
echo json_encode(['token' => $_SESSION['access_token'] ?? '']);
