<?php
require_once __DIR__ . '/../valida.php';

// Verifica se o método da requisição é POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_doacao = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    // Sanitização dos outros campos do formulário
    $id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
    $prioridade = filter_input(INPUT_POST, 'prioridade', FILTER_VALIDATE_INT);
    $meta = trim(strip_tags($_POST['meta']));

    // Verificar token CSRF para segurança
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token de segurança inválido!";
        header("Location: " . BASE_URL . "adm/recursos");
        exit;
    }

    try {
        $sql = "UPDATE doacoes 
                SET id_evento = ?, status = ?, prioridade = ?, meta_valor = ?
                WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("iiisi", $id_evento, $status, $prioridade, $meta, $id_doacao);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['resposta'] = "Recurso atualizado com sucesso!";
        } else {
            $_SESSION['resposta'] = "Nenhuma alteração foi detectada no recurso.";
        }

        $stmt->close();
        header("Location: " . BASE_URL . "adm/recursos");
        exit;

    } catch (Exception $erro) {
        $_SESSION['resposta'] = "Erro inesperado ao tentar atualizar o recurso. Tente novamente.";
        header("Location: " . BASE_URL . "adm/recursos");
        exit;
    }

} else {
    // Se o método não for POST, redireciona com uma mensagem de erro
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "adm/recursos");
    exit;
}
?>