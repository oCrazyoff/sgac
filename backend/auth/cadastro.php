<?php
require_once __DIR__ . '/../valida.php';
require_once __DIR__ . '/../auth/funcoes_auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Strings (removendo espaços e caracteres perigosos)
    $nome = trim(strip_tags($_POST['nome']));
    $email = trim(strip_tags($_POST['email']));
    $senha = trim(strip_tags($_POST["senha"]));

    // validar o nome
    $nome = validarNome($nome);

    // Verificar o email
    if (validarEmail($email) == false) {
        $_SESSION['resposta'] = "Email inválido!";
        header("Location: " . BASE_URL . "cadastro");
        exit;
    }

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "cadastro");
        exit;
    }

    //Validar senha
    if (validarSenha($senha) == false) {
        $_SESSION['resposta'] = "Senha inválida";
        header("Location: " . BASE_URL . "cadastro");
        exit;
    } else {
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
    }
    
    try {
        $sql = "INSERT INTO voluntarios (nome, email, senha) VALUES (?,?,?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha_hash);

        if ($stmt->execute()) {
            $_SESSION['resposta'] = "Voluntário cadastrado com sucesso!";
            header("Location: " . BASE_URL . "login");
            $stmt->close();
            exit;
        } else {
            $_SESSION['resposta'] = "Ocorreu um erro!";
            header("Location: " . BASE_URL . "cadastro");
            $stmt->close();
            exit;
        }
    } catch (Exception $erro) {
        // Caso houver erro ele retorna
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "cadastro");
                exit;
        }
    }
} else {
    $_SESSION['resposta'] = "Método de solicitação ínvalido!";
}

header("Location: " . BASE_URL . "cadastro");
$stmt = null;
exit;