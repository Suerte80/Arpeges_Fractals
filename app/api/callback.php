<?php

require_once __DIR__ . '/../utils/utils.php';

$client_id = 'f06f972b01fc4239b1579a55780eb3e0';
$client_secret = 'd5b4d2adaef24e6f8e89ef394e3afc47';
$redirect_uri = 'https://arpegesfractals.local/api/callback';

if (!isset($_GET['code'])) die('Erreur : code manquant');

$code = $_GET['code'];

// On constuit l'url pour récupérer le token
$token_response = file_get_contents('https://accounts.spotify.com/api/token', false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Authorization: Basic ' . base64_encode("$client_id:$client_secret"),
            'Content-Type: application/x-www-form-urlencoded'
        ],
        'content' => http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri
        ])
    ]
]));

$data = json_decode($token_response, true);

if (!isset($data['access_token'])) die('Erreur token');

$_SESSION['access_token'] = $data['access_token'];
$_SESSION['refresh_token'] = $data['refresh_token'];
$_SESSION['expires_at'] = time() + intval($data['expires_in']);

addNotification("info", "Connexion réussis !");
header('Location: /profile');
