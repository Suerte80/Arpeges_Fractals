<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/utils/utils.php';

require_once __DIR__ . '/../app/model/InitPDO.php';

require_once __DIR__ . '/../app/controller/LoginController.php';
require_once __DIR__ . '/../app/controller/SignupController.php';
require_once __DIR__ . '/../app/controller/LogoutController.php';
require_once __DIR__ . '/../app/controller/ProfileController.php';

// ON démare la session !
session_start();

// Initialisation de la customPDO
define('PDO', new InitPDO());

// Initialisation du gestionnaire d'image
define('IMAGE_MANAGER', new ImageManager(PDO));

$routes = [
    '/' => '../app/view/pages/acceuil.php',

    '/contact' => '../app/view/pages/contact.php',
    '/articles' => '../app/view/pages/articles.php',

    '/signup' => '../app/controller/SignupController.php',

    '/login' => '../app/view/pages/login.php',
    '/logout' => '../app/controller/LogoutController.php',

    '/profile' => '../app/controller/ProfileController.php',

    '/api/get_articles' => '../app/api/get_articles.php',
    '/api/notifications' => '../app/api/notifications.php',
    '/api/upload' => '../app/api/upload.php',
];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
if ($uri === '') $uri = '/';

// Recherche de la page correspondante
if (isset($routes[$uri])) {
    $pagePath = $routes[$uri];

    if (file_exists($pagePath)) {

        switch($uri) {
            case '/signup':
                $controller = new SignupController();
                $controller->handleSignup();
                break;
            case '/login':
                $controller = new LoginController();
                $controller->handleLogin();
                break;
            case '/logout':
                $controller = new LogoutController();
                $controller->handleLogout();
                break;
            case '/profile':
                $controller = new ProfileController();
                $controller->handleProfile();
                break;
            default:
                require $pagePath;
                break;
        }
    } else {
        http_response_code(500);
        echo "Erreur : fichier introuvable → $pagePath";
    }
} else {
    http_response_code(404);
    echo "404 - Page non trouvée";
}
