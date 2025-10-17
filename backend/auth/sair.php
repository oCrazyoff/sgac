<?php
    // função para definir o BASE_URL
    if (!defined('BASE_URL')) {
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            define('BASE_URL', '/sgac/');
        } else {
            define('BASE_URL', '/');
        }
    }   
    session_start();
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL . "login");
    exit();
?>