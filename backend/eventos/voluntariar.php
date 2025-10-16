<?php
require_once __DIR__ . '/../valida.php';

// Garante que o método da requisição seja POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id']) || !isset($_SESSION['cargo']) || $_SESSION['cargo'] != 1) {
        $_SESSION['resposta'] = "Acesso restrito. Por favor, faça login como voluntário.";
        header("Location: " . BASE_URL . "login");
        exit;
    }

    $id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_VALIDATE_INT);

    // Valida o token CSRF
    if (validarCSRF($_POST["csrf"]) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido. Atualize a página e tente novamente.";
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? BASE_URL));
        exit;
    }

    // Valida se o ID do evento é um número válido
    if (!$id_evento) {
        $_SESSION['resposta'] = "Evento inválido ou não encontrado.";
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? BASE_URL));
        exit;
    }

    $id_voluntario = $_SESSION['id'];
    $status_padrao = 1;

    try {
        $sql_check = "SELECT id FROM presencas WHERE id_voluntario = ? AND id_evento = ?";
        $stmt_check = $conexao->prepare($sql_check);
        $stmt_check->bind_param("ii", $id_voluntario, $id_evento);
        $stmt_check->execute();
        $resultado = $stmt_check->get_result();

        if ($resultado->num_rows > 0) {
            $_SESSION['resposta'] = "Você já está inscrito neste evento! ✅";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
        $stmt_check->close();

        // se não estiver inscrito, insere o novo registro
        $sql_insert = "INSERT INTO presencas (id_voluntario, id_evento, status) VALUES (?, ?, ?)";
        $stmt_insert = $conexao->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $id_voluntario, $id_evento, $status_padrao);

        if ($stmt_insert->execute()) {
            $_SESSION['resposta'] = "Inscrição realizada com sucesso! Agradecemos seu apoio. ❤️";
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao processar sua inscrição. Tente novamente.";
        }

        $stmt_insert->close();

    } catch (Exception $e) {
        $_SESSION['resposta'] = "Ocorreu um erro inesperado no sistema. Tente novamente mais tarde.";
    }

    // Redireciona o usuário de volta para a página de onde ele veio (a página do evento)
    header("Location: " . BASE_URL . "eventos");
    exit;
} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "eventos");
    exit;
}