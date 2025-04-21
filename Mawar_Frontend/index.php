<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        require __DIR__ . '/resources/views/index.php';
        break;
    case '/login':
        require __DIR__ . '/resources/views/login.php';
        break;
    case '/register':
        require __DIR__ . '/resources/views/manager_register.php';
        break;
    case '/admin_register':
        require __DIR__ . '/resources/views/admin_register.php';
        break;
    case '/staff_register':
        require __DIR__ . '/resources/views/staff_register.php';
        break;
    case '/manager_register':
        require __DIR__ . '/resources/views/manager_register.php';
        break;
    case '/we':
        require __DIR__ . '/resources/views/we.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/resources/views/404.php';
        break;
}