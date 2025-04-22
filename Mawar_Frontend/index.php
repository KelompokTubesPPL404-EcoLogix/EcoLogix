<?php
session_start();

// Define base path with correct separators
define('BASE_PATH', str_replace('/', DIRECTORY_SEPARATOR, __DIR__));

$request = $_SERVER['REQUEST_URI'];
$request = parse_url($request, PHP_URL_PATH);
$request = rtrim($request, '/');

switch ($request) {
    case '':
    case '/':
        require BASE_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'index.php';
        break;
    case '/login':
        require BASE_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'login.php';
        break;
    case '/contact':
        require BASE_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'contact.php';
        break;
    case '/about':
        require BASE_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'about.php';
        break;
    default:
        http_response_code(404);
        require BASE_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . '404.php';
        break;
}