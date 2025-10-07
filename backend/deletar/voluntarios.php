<?php
require_once __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ID do voluntário
    $id = intval($_POST['id']);

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido!";
        header("Location: " . BASE_URL . "adm/voluntarios");
        exit;
    }

    try {
        // Verifica se o ID existe
        $sql_check = "SELECT 1 FROM voluntarios WHERE id = ?";
        $stmt_check = $conexao->prepare($sql_check);
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $resultado = $stmt_check->get_result();

        if ($resultado->num_rows === 0) {
            $_SESSION['resposta'] = "Voluntário não encontrado!";
            header("Location: " . BASE_URL . "adm/voluntarios");
            $stmt_check->close();
            exit;
        }
        $stmt_check->close();

        // Excluir o voluntário
        $sql = "DELETE FROM voluntarios WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['resposta'] = "Voluntário excluído com sucesso!";
        } else {
            $_SESSION['resposta'] = "Nenhuma exclusão realizada!";
        }

        $stmt->close();
        header("Location: " . BASE_URL . "adm/voluntarios");
        exit;

    } catch (Exception $erro) {
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "adm/voluntarios");
                exit;
        }
    }

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "adm/voluntarios");
    exit;
}
?>
