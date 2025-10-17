<?php
require_once __DIR__ . '/../valida.php';
require_once __DIR__ . '/../auth/funcoes_auth.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ID do voluntário
    $id = intval($_GET['id']);

    // Strings (removendo espaços e caracteres perigosos)
    $nome = trim(strip_tags($_POST['nome']));
    $email = trim(strip_tags($_POST['email']));
    $telefone = trim(strip_tags($_POST['telefone']));
    $habilidades = trim(strip_tags($_POST['habilidades']));
    $senha = trim(strip_tags($_POST["senha"]));

    // Validar senha
    if($senha != ""){
    if (validarSenha($senha) == false) {
        $_SESSION['resposta'] = "Senha inválida!";
        header("Location: " . BASE_URL . "adm/perfil");
        exit;
    }
    }

    // Validar nome
    $nome = validarNome($nome);
    if ($nome === false) {
        $_SESSION['resposta'] = "Nome inválido!";
        header("Location: " . BASE_URL . "adm/perfil");
        exit;
    }

    // Validar email
    if (validarEmail($email) == false) {
        $_SESSION['resposta'] = "Email inválido!";
        header("Location: " . BASE_URL . "adm/perfil");
        exit;
    }

    // Validar telefone
    if (validarTelefone($telefone) == false) {
        $_SESSION['resposta'] = "Telefone inválido!";
        header("Location: " . BASE_URL . "adm/perfil");
        exit;
    }

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (validarCSRF($csrf) == false) {
        $_SESSION['resposta'] = "Token Inválido";
        header("Location: " . BASE_URL . "adm/perfil");
        exit;
    }

    try {
        if($senha != ""){
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
            $sql = "UPDATE voluntarios SET nome = ?, email = ?, telefone = ?, habilidades = ?, senha = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("sssssi", $nome, $email, $telefone, $habilidades, $senha_hash, $id);
        } else {
            $sql = "UPDATE voluntarios SET nome = ?, email = ?, telefone = ?, habilidades = ? WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("ssssi", $nome, $email, $telefone, $habilidades, $id);
        }

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['resposta'] = "Voluntário atualizado com sucesso!";
            header("Location: " . BASE_URL . "adm/perfil");
            exit;
        } else {
            $_SESSION['resposta'] = "Nada a ser alterado.";
            header("Location: " . BASE_URL . "adm/perfil");
            exit;
        }

        $stmt->close();

    } catch (Exception $erro) {
        switch ($erro->getCode()) {
            default:
                $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
                header("Location: " . BASE_URL . "adm/perfil");
                exit;
        }
    }

} else {
    $_SESSION['resposta'] = "Método de solicitação inválido!";
    header("Location: " . BASE_URL . "adm/perfil");
    exit;
}
?>