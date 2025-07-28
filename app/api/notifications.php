<?php

// On restreint l'accés au utilisateurs qui n'ont pas de session
if(!isset($_SESSION['user-id'])){
    http_response_code(403);
    echo json_encode(["error" => "Access denied"]);
    exit;
}

// On récupère la/les notification(s) dans $notif
$notif = $_SESSION['notifications'] ?? [];
unset($_SESSION['notifications']);

error_log(json_encode($notif));

// On écrit la/les notification(s) dans un format json sur la page.
header('Content-Type: application/json');
echo json_encode($notif);