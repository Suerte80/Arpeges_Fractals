<?php
header('Content-Type: application/json');
echo json_encode(['token' => $_SESSION['access_token'] ?? '']);
