<?php
// TODO Sécurisé l'accés car la c'est léger.
header('Content-Type: application/json');
echo json_encode([
    'token' => $_SESSION['access_token'] ?? '',
    'expires_at' => $_SESSION['expires_at'] ?? ''
]);
