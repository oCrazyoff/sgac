<?php
require_once __DIR__ . '/../valida.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitização básica (remoção de tags e espaços)
    $link_img = trim(strip_tags($_POST['link_img']));
    $nome = trim(strip_tags($_POST['nome']));
    $descricao = trim(strip_tags($_POST['descricao']));
    $tipo = trim(strip_tags($_POST['tipo']));
    $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
    $data_hora = trim(strip_tags($_POST['data_hora']));
    $endereco = trim(strip_tags($_POST['endereco']));
    $meta_voluntarios = filter_input(INPUT_POST, 'meta_voluntarios', FILTER_VALIDATE_INT);
    $objetivos = trim(strip_tags($_POST['objetivos']));

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "adm/eventos");
        exit;
    }

    // Validações simples
    if (!isset($nome) || !isset($tipo) || !isset($endereco) || !$data_hora) {
        $_SESSION['resposta'] = "Preencha todos os campos obrigatórios.";
        header("Location: " . BASE_URL . "adm/eventos");
        exit;
    }

    // Garante formato de data válido
    $dataFormatada = date('Y-m-d H:i:s', strtotime($data_hora));

    try {
        $sql = "INSERT INTO eventos 
                (link_img, nome, descricao, tipo, status, data_hora, endereco, meta_voluntarios, objetivos)
                VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param(
            "ssssissis",
            $link_img,
            $nome,
            $descricao,
            $tipo,
            $status,
            $dataFormatada,
            $endereco,
            $meta_voluntarios,
            $objetivos
        );

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Evento cadastrado com sucesso!";
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro ao cadastrar o evento.";
        }

        $stmt->close();
        header("Location: " . BASE_URL . "adm/eventos");
        exit;
    } catch (Exception $erro) {
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "adm/eventos");
                exit;
        }
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "adm/eventos");
    exit;
}