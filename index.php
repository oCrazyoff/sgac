<?php
$url = $_GET['url'] ?? '';
$url = trim($url, '/');

// rotas
$routes = [
    '' => 'pages/eventos.php',

    // autenticação
    'login' => 'pages/login_form.php',
    'fazer_login' => 'backend/auth/login.php',

    // rotas do usuario adm
    'adm/doacoes' => 'adm/doacoes.php',
    'adm/eventos' => 'adm/eventos.php',
    'adm/presenca' => 'adm/presenca.php',
    'adm/relatorios' => 'adm/relatorios.php',
    'adm/voluntarios' => 'adm/voluntarios.php',

    // rotas de busca
    'buscar_voluntarios' => 'backend/buscar/voluntarios.php',

    // rotas de cadastro
    'adm/cadastrar_voluntarios' => 'backend/cadastrar/voluntarios.php',

    // rotas de edição
    'adm/editar_voluntarios' => 'backend/editar/voluntarios.php',

    // rotas de deletar
    'adm/deletar_voluntarios' => 'backend/deletar/voluntarios.php',
];

if (array_key_exists($url, $routes)) {
    require $routes[$url];
    exit;
}

http_response_code(404);
require 'erro404.php';