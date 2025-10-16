<?php
require_once __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ID do evento
    $id = intval($_POST['id']);

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido!";
        header("Location: " . BASE_URL . "adm/recursos");
        exit;
    }

    try {
        // Verifica se o ID existe
        $sql_check = "SELECT 1 FROM doacoes WHERE id = ?";
        $stmt_check = $conexao->prepare($sql_check);
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $resultado = $stmt_check->get_result();

        if ($resultado->num_rows === 0) {
            $_SESSION['resposta'] = "Doação não encontrado!";
            header("Location: " . BASE_URL . "adm/recursos");
            $stmt_check->close();
            exit;
        }
        $stmt_check->close();

        // Excluir o evento
        $sql = "DELETE FROM doacoes WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['resposta'] = "Doação excluída com sucesso!";
        } else {
            $_SESSION['resposta'] = "Nenhuma exclusão realizada!";
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
?>