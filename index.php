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
    'adm/recursos' => 'adm/recursos.php',
    'adm/eventos' => 'adm/eventos.php',
    'adm/presenca' => 'adm/presenca.php',
    'adm/presenca/lista' => 'adm/lista_presenca.php',
    'adm/relatorios' => 'adm/relatorios.php',
    'adm/voluntarios' => 'adm/voluntarios.php',

    // rotas de busca
    'buscar_voluntarios' => 'backend/buscar/voluntarios.php',
    'buscar_eventos' => 'backend/buscar/eventos.php',

    // rotas de cadastro
    'adm/cadastrar_voluntarios' => 'backend/cadastrar/voluntarios.php',
    'adm/cadastrar_eventos' => 'backend/cadastrar/eventos.php',
    'adm/cadastrar_recursos' => 'backend/cadastrar/recursos.php',

    // rotas de edição
    'adm/editar_voluntarios' => 'backend/editar/voluntarios.php',
    'adm/editar_eventos' => 'backend/editar/eventos.php',
    'adm/alterar_presenca' => 'backend/editar/presenca.php',

    // rotas de deletar
    'adm/deletar_voluntarios' => 'backend/deletar/voluntarios.php',
    'adm/deletar_eventos' => 'backend/deletar/eventos.php',
    'adm/deletar_recursos' => 'backend/deletar/recursos.php',
];

if (array_key_exists($url, $routes)) {
    require $routes[$url];
    exit;
}

http_response_code(404);
require 'erro404.php';