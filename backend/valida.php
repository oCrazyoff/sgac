<?php
require_once "conexao.php";

// Verifica se a sessão está ativa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Verifica se há login ativo
if (
    !isset($_SESSION["id"]) ||
    !isset($_SESSION["nome"]) ||
    !isset($_SESSION["email"]) ||
    !isset($_SESSION["cargo"])
) {
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL . "login");
    exit();
}

// Define a tabela conforme o cargo
$tabela = ($_SESSION["cargo"] == 0) ? "adm" : "voluntarios";

$id = $_SESSION["id"];
$stmt = $conexao->prepare("SELECT nome, email FROM $tabela WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->bind_result($nome, $email);
    $stmt->fetch();
    $stmt->close();

    if (($nome === null) || ($email === null)) {
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "login");
        exit();
    } else {
        $_SESSION["nome"] = $nome;
        $_SESSION["email"] = $email;
    }
} else {
    $_SESSION['resposta'] = "Erro inesperado!";
    header("Location: " . BASE_URL . "login");
    exit();
}