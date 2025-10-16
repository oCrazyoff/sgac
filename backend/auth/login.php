<?php
session_start();
require_once "funcoes_auth.php";
require_once "backend/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim(strip_tags($_POST['email']));
    $senha = trim($_POST["senha"]);

    // Verificar o email
    if (!validarEmail($email)) {
        $_SESSION['resposta'] = "Email inválido!";
        header("Location: " . BASE_URL . "login");
        exit;
    }

    // Verificar token CSRF
    $csrf = trim(strip_tags($_POST["csrf"]));
    if (!validarCSRF($csrf)) {
        $_SESSION['resposta'] = "Método inválido!";
        header("Location: " . BASE_URL . "login");
        exit;
    }

    // Validar senha
    if (!validarSenha($senha)) {
        $_SESSION['resposta'] = "Senha inválida!";
        header("Location: " . BASE_URL . "login");
        exit;
    }

    if (!empty($email) && !empty($senha)) {
        try {
            // Tenta buscar como ADM
            $stmt = $conexao->prepare("SELECT id, nome, email, senha FROM adm WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();
            $stmt->close();

            $cargo = null; // 0 = ADM, 1 = Voluntário

            // Se não achou como ADM, tenta como voluntário
            if (!$usuario) {
                $stmt = $conexao->prepare("SELECT id, nome, email, telefone, senha FROM voluntarios WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $usuario = $result->fetch_assoc();
                $stmt->close();

                if ($usuario) {
                    $cargo = 1; // Voluntário
                }
            } else {
                $cargo = 0; // ADM
            }

            // Se não encontrou em nenhuma tabela
            if (!$usuario) {
                $_SESSION['resposta'] = "E-mail ou senha incorretos!";
                header("Location: " . BASE_URL . "login");
                exit;
            }

            // Verifica senha
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION["id"] = $usuario['id'];
                $_SESSION["nome"] = $usuario['nome'];
                $_SESSION["email"] = $usuario['email'];
                $_SESSION["cargo"] = $cargo;
                $_SESSION['resposta'] = "Bem-vindo, " . $usuario['nome'] . "!";

                if ($cargo === 0) {
                    header("Location: " . BASE_URL . "adm/voluntarios");
                } else {
                    $_SESSION['telefone'] = $usuario['telefone'];
                    header("Location: " . BASE_URL . "eventos");
                }
                exit;
            } else {
                $_SESSION['resposta'] = "E-mail ou senha incorretos!";
                header("Location: " . BASE_URL . "login");
                exit;
            }

        } catch (Exception $erro) {
            $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
            header("Location: " . BASE_URL . "login");
            exit;
        }
    } else {
        $_SESSION['resposta'] = "Preencha todas as informações!";
    }
} else {
    $_SESSION['resposta'] = "Variável POST inválida!";
}

header("Location: " . BASE_URL . "login");
exit;