<?php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Photo.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/PhotoController.php';
require_once __DIR__ . '/../controllers/CommentController.php';
require_once __DIR__ . '/../controllers/HomeController.php';

$router = new Router();

$router->add('GET',  '/login',    ['AuthController', 'showLogin']);
$router->add('POST', '/login',    ['AuthController', 'login']);
$router->add('GET',  '/register', ['AuthController', 'showRegister']);
$router->add('POST', '/register', ['AuthController', 'register']);
$router->add('GET',  '/logout',   ['AuthController', 'logout']);

$router->add('GET',  '/photos',            ['PhotoController', 'index']);
$router->add('GET',  '/photo/upload',      ['PhotoController', 'showUploadForm']);
$router->add('POST', '/photo/store',       ['PhotoController', 'store']);
$router->add('GET',  '/photo/{id}',        ['PhotoController', 'show']);
$router->add('GET',  '/photo/{id}/delete', ['PhotoController', 'delete']);

$router->add('POST', '/comment/store', ['CommentController', 'store']);

$router->add('GET', '/', ['HomeController', 'index']);
$router->add('GET', '/about', ['HomeController', 'about']);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($scriptDir !== '' && strpos($requestUri, $scriptDir) === 0) {
    $requestUri = substr($requestUri, strlen($scriptDir));
}

if ($requestUri === '' || $requestUri === false) {
    $requestUri = '/';
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $requestUri);
