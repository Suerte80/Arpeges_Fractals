<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/model/InitPDO.php';

define('PDO', new InitPDO());

$routes = [
    '/' => '../app/view/pages/acceuil.php',
    '/contact' => '../app/view/pages/contact.php',
    '/articles' => '../app/view/pages/articles.php',
    '/signup' => '../app/controller/SignupController.php',
    '/api/get_articles' => '../app/api/get_articles.php',
];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
if ($uri === '') $uri = '/';

// Recherche de la page correspondante
if (isset($routes[$uri])) {
    $pagePath = $routes[$uri];

    if (file_exists($pagePath)) {
        if ($uri === '/signup') {
            require_once $pagePath;
            $controller = new SignupController();
            $controller->handleSignup();
        } else{
            require $pagePath;
        }
    } else {
        http_response_code(500);
        echo "Erreur : fichier introuvable → $pagePath";
    }
} else {
    http_response_code(404);
    echo "404 - Page non trouvée";
}
