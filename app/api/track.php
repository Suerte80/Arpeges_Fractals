<?php
// /api/track.php
declare(strict_types=1);

// Pas de session_start() ici si ton routeur l'a déjà fait.
ini_set('display_errors', '0'); // évite d'injecter du HTML d'erreur dans la réponse
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// ✔︎ Renvoie un JSON 401 propre à la place :
if (empty($_SESSION['user-is-connected'])) {
    http_response_code(401);
    echo json_encode(['error' => 'not_authenticated']);
    exit;
}

$token = $_SESSION['access_token'] ?? null;
if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'no_access_token']);
    exit;
}

function callSpotify(string $url, string $token): array
{
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => "Authorization: Bearer {$token}\r\nAccept: application/json\r\n",
            'ignore_errors' => true, // pour récupérer le body même en 4xx/5xx
        ]
    ];
    $context = stream_context_create($opts);
    $raw = @file_get_contents($url, false, $context);

    // Lire le code HTTP depuis $http_response_header
    $status = 0;
    $headers = [];
    if (isset($http_response_header)) {
        foreach ($http_response_header as $h) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#i', $h, $m)) $status = (int)$m[1];
            else {
                $parts = explode(':', $h, 2);
                if (count($parts) === 2) $headers[strtolower(trim($parts[0]))] = trim($parts[1]);
            }
        }
    }

    return [
        'status' => $status,
        'headers' => $headers,
        'raw' => $raw,
        'json' => $raw ? json_decode($raw, true) : null,
    ];
}

// 1) Tente currently-playing
$r = callSpotify('https://api.spotify.com/v1/me/player/currently-playing', $token);

// 204 = rien en cours
if ($r['status'] === 204) {
    echo json_encode(['error' => 'no_track_playing']);
    exit;
}

// 401 = token invalide/expiré (ton routeur a dit token OK pour la lecture, mais côté Web API ça peut différer)
if ($r['status'] === 401) {
    http_response_code(401);
    echo json_encode(['error' => 'token_invalid_or_expired']);
    exit;
}

// Si autre erreur ou pas de JSON exploitable, essaie /me/player pour au moins avoir item
if ($r['status'] >= 400 || !$r['json']) {
    $r = callSpotify('https://api.spotify.com/v1/me/player', $token);
}

if ($r['status'] >= 400 || !$r['json']) {
    http_response_code($r['status'] ?: 500);
    echo json_encode([
        'error' => 'spotify_api_error',
        'status' => $r['status'],
        'body'   => $r['raw'],
    ]);
    exit;
}

$data = $r['json'];
if (empty($data['item'])) {
    echo json_encode(['error' => 'no_track_playing']);
    exit;
}

$track  = $data['item'];
$artist = implode(', ', array_map(fn($a) => $a['name'], $track['artists'] ?? []));
$title  = $track['name'] ?? null;
$image  = $track['album']['images'][0]['url'] ?? null;

echo json_encode([
    'title'  => $title,
    'artist' => $artist,
    'image'  => $image,
], JSON_UNESCAPED_UNICODE);
exit; // coupe toute sortie du routeur/layout en aval
