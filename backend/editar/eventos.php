<?php
require_once __DIR__ . '/../valida.php';
require_once __DIR__ . '/../auth/funcoes_auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ID do evento
    $id = intval($_GET['id']);

    // Sanitização e limpeza dos campos
    $link_img = trim(strip_tags($_POST['link_img']));
    $nome = trim(strip_tags($_POST['nome']));
    $descricao = trim(strip_tags($_POST['descricao']));
    $tipo = trim(strip_tags($_POST['tipo']));
    $status = intval($_POST['status']);
    $data_hora = trim(strip_tags($_POST['data_hora']));
    $endereco = trim(strip_tags($_POST['endereco']));
    $meta_voluntarios = intval($_POST['meta_voluntarios']);
    $objetivos = trim(strip_tags($_POST['objetivos']));

    // Validação de nome
    $nome = validarNome($nome);
    if ($nome === false) {
        $_SESSION['resposta'] = "Nome inválido!";
        header("Location: " . BASE_URL . "adm/eventos");
        exit;
    }

    // Validação de tipo
    $tipo = validarNome($tipo);
    if ($tipo === false) {
        $_SESSION['resposta'] = "Tipo de evento inválido!";
        header("Location: " . BASE_URL . "adm/eventos");
        exit;
    }

    // Validação de data e hora
    if (strtotime($data_hora) === false) {
        $_SESSION['resposta'] = "Data e hora inválidas!";
        header("Location: " . BASE_URL . "adm/eventos");
        exit;
    }

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido!";
        header("Location: " . BASE_URL . "adm/eventos");
        exit;
    }

    try {
        // Atualiza o evento com base no ID
        $sql = "UPDATE eventos 
                SET link_img = ?, nome = ?, descricao = ?, tipo = ?, status = ?, 
                    data_hora = ?, endereco = ?, meta_voluntarios = ?, objetivos = ?
                WHERE id = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param(
            "ssssissisi",
            $link_img,
            $nome,
            $descricao,
            $tipo,
            $status,
            $data_hora,
            $endereco,
            $meta_voluntarios,
            $objetivos,
            $id
        );

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['resposta'] = "Evento atualizado com sucesso!";
        } else {
            $_SESSION['resposta'] = "Nenhuma alteração detectada ou erro inesperado.";
        }

        $stmt->close();
        header("Location: " . BASE_URL . "adm/eventos");
        exit;

    } catch (Exception $erro) {
        $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
        header("Location: " . BASE_URL . "adm/eventos");
        exit;
    }

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "adm/eventos");
    exit;
}
?>