<?php
$url = $_GET['url'] ?? '';
$url = trim($url, '/');

// rotas
$routes = [
    '' => 'pages/eventos.php',

    // autenticação
    'login' => 'pages/login_form.php',
    'fazer_login' => 'backend/auth/login.php',
];

if (array_key_exists($url, $routes)) {
    require $routes[$url];
    exit;
}

http_response_code(404);
require 'erro404.php';