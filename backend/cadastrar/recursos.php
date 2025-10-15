<?php
require_once __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitização básica (remoção de tags e espaços)
    $id_evento = trim(strip_tags($_POST['id_evento']));
    $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
    $prioridade = filter_input(INPUT_POST, 'prioridade', FILTER_VALIDATE_INT);
    $meta = filter_input(INPUT_POST, 'meta', FILTER_VALIDATE_INT);

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "adm/recursos");
        exit;
    }

    // Validações simples
    if (!isset($id_evento) || !isset($status) || !isset($prioridade) || !$meta) {
        $_SESSION['resposta'] = "Preencha todos os campos obrigatórios.";
        header("Location: " . BASE_URL . "adm/recursos");
        exit;
    }

    try {
        $sql = "INSERT INTO doacoes (id_evento, status, prioridade, meta_valor) VALUES (?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param(
            "iiii",
            $id_evento,
            $status,
            $prioridade,
            $meta,
        );

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Doação cadastrada com sucesso!";
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao cadastrar doação.";
        }

        $stmt->close();
        header("Location: " . BASE_URL . "adm/recursos");
        exit;
    } catch (Exception $erro) {
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "adm/recursos");
                exit;
        }
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "adm/recursos");
    exit;
}