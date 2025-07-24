<?php

$routes = [
    '/' => '../app/view/pages/acceuil.php',
    '/contact' => '../app/view/pages/contact.php',
    '/articles' => '../app/view/pages/articles.php',
    '/api/get_articles' => '../app/api/get_articles.php',
];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
if ($uri === '') $uri = '/';

// Recherche de la page correspondante
if (isset($routes[$uri])) {
    $pagePath = $routes[$uri];

    if (file_exists($pagePath)) {
        require $pagePath;
    } else {
        http_response_code(500);
        echo "Erreur : fichier introuvable → $pagePath";
    }
} else {
    http_response_code(404);
    echo "404 - Page non trouvée";
}
