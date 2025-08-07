<?php

if (!isset($_SESSION['user-is-connected'])) {
    addNotification('error', 'Nous n\'êtes pas connecté !');
    header('location: /');
}

$token = $_SESSION['access_token'] ?? null;

if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'No access token']);
    exit;
}

$opts = [
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer $token\r\n"
    ]
];

$context = stream_context_create($opts);
$response = file_get_contents("https://api.spotify.com/v1/me/player/currently-playing", false, $context);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Spotify API request failed']);
    exit;
}

$data = json_decode($response, true);

if (empty($data['item'])) {
    echo json_encode(['error' => 'No track playing']);
    exit;
}

$track = $data['item'];
$artist = implode(', ', array_map(fn($a) => $a['name'], $track['artists']));
$title = $track['name'];
$image = $track['album']['images'][0]['url'] ?? null;

echo json_encode([
    'title' => $title,
    'artist' => $artist,
    'image' => $image,
]);
