<?php

// On récupère la/les notification(s) dans $notif
$notif = $_SESSION['notifications'] ?? [];
unset($_SESSION['notifications']);

error_log(json_encode($notif));

// On écrit la/les notification(s) dans un format json sur la page.
header('Content-Type: application/json');
echo json_encode($notif);