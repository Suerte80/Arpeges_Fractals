<?php
$accessToken = $_SESSION['access_token'] ?? null;
$playlistUri = $_GET['uri'] ?? null;
$deviceId = $_GET['device_id'] ?? null;

if (!$accessToken || !$playlistUri || !$deviceId) {
    http_response_code(400);
    echo json_encode(['error' => 'Paramètre manquant']);
    exit;
}

$url = 'https://api.spotify.com/v1/me/player/play?device_id=' . urlencode($deviceId);

$data = [
    'context_uri' => $playlistUri,
    'offset' => ['position' => 0],
    'position_ms' => 0
];

$options = [
    'http' => [
        'method'  => 'PUT',
        'header'  => [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ],
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Lecture échouée']);
    exit;
}

http_response_code(204); // no content, lecture OK
echo json_encode(['success' => true]);
