<?php
require_once __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_doacao = filter_input(INPUT_POST, 'id_doacao', FILTER_VALIDATE_INT);
    $valor_doado = trim(strip_tags($_POST['valor']));

    // Validação do Token CSRF
    if (validarCSRF($_POST["csrf"]) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido. Tente novamente.";
        header("Location: " . BASE_URL . "recursos");
        exit;
    }

    // Validação dos campos
    if (!$id_doacao || $valor_doado <= 0) {
        $_SESSION['resposta'] = "Dados inválidos. Verifique o valor e tente novamente.";
        header("Location: " . BASE_URL . "recursos");
        exit;
    }

    try {
        $sql_find_doacao = "SELECT id FROM doacoes WHERE id = ? LIMIT 1";
        $stmt = $conexao->prepare($sql_find_doacao);
        $stmt->bind_param("i", $id_doacao);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();

        if ($resultado->num_rows === 0) {
            // Se não existir uma campanha de doação para este evento, retorna um erro.
            $_SESSION['resposta'] = "Nenhuma campanha de doação ativa encontrada.";
            header("Location: " . BASE_URL . "recursos");
            exit;
        }

        $nome_doador = $_SESSION['nome'];
        $telefone_doador = $_SESSION['telefone'];
        $data_hoje = date('Y-m-d');

        $sql_insert = "INSERT INTO itens_doacao (id_doacao, nome_doador, numero_contato, data_doacao, valor) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql_insert);
        $stmt->bind_param(
            "isssd",
            $id_doacao,
            $nome_doador,
            $telefone_doador,
            $data_hoje,
            $valor_doado
        );

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Doação realizada com sucesso! Muito obrigado.";
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao processar sua doação. Tente novamente.";
        }

        $stmt->close();
        header("Location: " . BASE_URL . "recursos");
        exit;

    } catch (Exception $erro) {
        // Tratamento de erro genérico
        $_SESSION['resposta'] = "Erro inesperado no sistema. Tente novamente mais tarde.";
        header("Location: " . BASE_URL . "recursos");
        exit;
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "recursos");
    exit;
}