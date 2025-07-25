<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/model/InitPDO.php';

// ON démare la session !
session_start();

define('PDO', new InitPDO());

$routes = [
    '/' => '../app/view/pages/acceuil.php',
    '/contact' => '../app/view/pages/contact.php',
    '/articles' => '../app/view/pages/articles.php',
    '/signup' => '../app/controller/SignupController.php',
    '/login' => '../app/view/pages/login.php',

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

        switch($pagePath) {
            case '/signup':
                $controller = new SignupController();
                $controller->handleSignup();
                break;
            case '/login':
                $controller = new LoginController();
                $controller->handleLogin();
                break;
            default:
                break;

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
