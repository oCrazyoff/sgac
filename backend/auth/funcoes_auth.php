<?php
function validarNome($nome)
{
    // Remove espaços no início/fim
    $nome = trim($nome);

    // Remove tudo que não for letra ou espaço
    $nome = preg_replace('/[^\\p{L} ]/u', '', $nome);

    // Se após a limpeza não sobrar nada válido
    if (mb_strlen($nome) < 3 || mb_strlen($nome) > 50) {
        return false;
    }

    // Formata para primeira letra maiúscula em cada palavra
    $nome = mb_convert_case($nome, MB_CASE_TITLE, "UTF-8");

    return $nome;
}
function validarEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}
function validarTelefone($telefone)
{
    // Remove espaços, parênteses, traços e outros símbolos
    $telefone = preg_replace('/[^0-9]/', '', $telefone);

    // Verifica se tem entre 10 e 11 dígitos (fixo ou celular com DDD)
    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
        return false;
    }

    // Valida DDD (01 a 99)
    $ddd = substr($telefone, 0, 2);
    if ($ddd < 11 || $ddd > 99) {
        return false;
    }

    // Se tiver 11 dígitos, o terceiro deve ser 9 (celular)
    if (strlen($telefone) == 11 && $telefone[2] != '9') {
        return false;
    }

    // Formata o número
    if (strlen($telefone) == 11) {
        // Formato (XX) 9XXXX-XXXX
        $telefone = sprintf('(%s) %s %s-%s',
            substr($telefone, 0, 2),
            substr($telefone, 2, 1),
            substr($telefone, 3, 4),
            substr($telefone, 7)
        );
    } else {
        // Formato (XX) XXXX-XXXX
        $telefone = sprintf('(%s) %s-%s',
            substr($telefone, 0, 2),
            substr($telefone, 2, 4),
            substr($telefone, 6)
        );
    }

    return $telefone;
}
function validarSenha($senha)
{
    // Pelo menos 8 caracteres, uma letra maiúscula, uma letra minúscula, um número e um caractere especial
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $senha)) {
        return false;
    }

    return true;
}
?>