<?php
require_once __DIR__ . '/../valida.php';
require_once __DIR__ . '/../auth/funcoes_auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ID do voluntário
    $id_voluntario = intval($_POST['id_voluntario']);
    $id_evento = intval($_POST["id_evento"]);

    // Sanitização e limpeza dos campos
    $status = intval($_POST['status']);

    // Verificar Status
    if(!isset($status) || ($status !== 0 && $status !== 1)){
        $_SESSION['resposta'] = "Status inválido!";
        header("Location: " . BASE_URL . "adm/presenca/lista?id=" . $id_evento);
        exit;
    }

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido!";
        header("Location: " . BASE_URL . "adm/presenca/lista?id=" . $id_evento);
        exit;
    }

    try {
        if($status == 1){
            $sql = "UPDATE presencas SET status = 1 WHERE id_voluntario = ? AND id_evento = ?";
        } else {
            $sql = "UPDATE presencas SET status = 0 WHERE id_voluntario = ? AND id_evento = ?";
        }
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii",$id_voluntario, $id_evento);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Presença atualizada com sucesso!";
        } else {
            $_SESSION['resposta'] = "Nenhuma alteração detectada ou erro inesperado.";
        }

        $stmt->close();
        header("Location: " . BASE_URL . "adm/presenca/lista?id=" . $id_evento);
        exit;

    } catch (Exception $erro) {
        $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
        header("Location: " . BASE_URL . "adm/presenca/lista?id=" . $id_evento);
        exit;
    }

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "adm/presenca/lista?id=" . $id_evento);
    exit;
}
?>