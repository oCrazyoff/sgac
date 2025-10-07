<?php
require_once __DIR__ . '/../valida.php';
require_once __DIR__ . '/../auth/funcoes_auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Strings (removendo espaços e caracteres perigosos)
    $nome = trim(strip_tags($_POST['nome']));
    $email = trim(strip_tags($_POST['email']));
    $telefone = trim(strip_tags($_POST['telefone']));
    $habilidades = trim(strip_tags($_POST['habilidades']));

    // validar o nome
    $nome = validarNome($nome);

    // Verificar o email
    if (validarEmail($email) == false) {
        $_SESSION['resposta'] = "Email inválido!";
        header("Location: " . BASE_URL . "adm/voluntarios");
        exit;
    }

    // verificar telefone
    if (validarTelefone($telefone) == false) {
        $_SESSION['resposta'] = "Telefone inválido!";
        header("Location: " . BASE_URL . "adm/voluntarios");
        exit;
    }

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "adm/voluntarios");
        exit;
    }
    try {
        $sql = "INSERT INTO voluntarios (nome, email, telefone, habilidades) VALUES (?,?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssss", $nome, $email, $telefone, $habilidades);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Voluntário cadastrado com sucesso!";
            header("Location: " . BASE_URL . "adm/voluntarios");
            $stmt->close();
            exit;
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro!";
            header("Location: " . BASE_URL . "adm/voluntarios");
            $stmt->close();
            exit;
        }
    } catch (Exception $erro) {
        // Caso houver erro ele retorna
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "adm/voluntarios");
                exit;
        }
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação ínvalido!";
}

header("Location: " . BASE_URL . "adm/voluntarios");
$stmt = null;
exit;