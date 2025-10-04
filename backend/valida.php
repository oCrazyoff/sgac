<?php
require_once "conexao.php";

//Verifica se existe uma sessão ativa e se não houver inicia uma
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION["id"]) && !isset($_SESSION["nome"]) && !isset($_SESSION["email"])) {
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL);
    exit();
} else {
    $id = $_SESSION["id"];
    $stmt = $conexao->prepare("SELECT nome, email FROM adm WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->bind_result($nome, $email);
        $stmt->fetch();
        $stmt->close();

        if (($nome === null) || ($email === null)) {
            session_unset();
            session_destroy();
            header("Location: " . BASE_URL);
            exit();
        } else {
            $_SESSION["nome"] = $nome;
            $_SESSION["email"] = $email;
        }
    } else {
        $_SESSION['resposta'] = "Erro inesperado!";
        header("Location: " . BASE_URL);
        exit();
    }
}