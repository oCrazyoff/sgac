<?php
// Função para carregar variáveis do .env
function carregarEnv($caminho)
{
    if (!file_exists($caminho)) {
        throw new Exception("Arquivo .env não encontrado em: " . $caminho);
    }

    $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        // Ignorar comentários
        if (strpos(trim($linha), '#') === 0) {
            continue;
        }

        // Separar chave e valor
        list($chave, $valor) = explode('=', $linha, 2);

        $chave = trim($chave);
        $valor = trim($valor, " \"'"); // já remove espaços e aspas

        // Salvar no ambiente
        putenv("$chave=$valor");
        $_ENV[$chave] = $valor;
    }
}

// função para definir o BASE_URL
if (!defined('BASE_URL')) {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define('BASE_URL', '/sgac/');
    } else {
        define('BASE_URL', '/');
    }
}

function gerarCSRF()
{
    $_SESSION["csrf"] = (isset($_SESSION["csrf"])) ? $_SESSION["csrf"] : hash('sha256', random_bytes(32));

    return ($_SESSION["csrf"]);
}

function validarCSRF($csrf)
{
    if (!isset($_SESSION["csrf"])) {
        return (false);
    }
    if ($_SESSION["csrf"] !== $csrf) {
        return false;
    }
    if (!hash_equals($_SESSION["csrf"], $csrf)) {
        return false;
    }

    return true;
}

function formatarReal($valor)
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function formatarData($data)
{
    return date('d/m/Y', strtotime($data));
}

function formatarTelefone($numero) {
    // Remove tudo que não for número
    $numero = preg_replace('/\D/', '', $numero);

    // Verifica se tem DDD e número válidos
    if (strlen($numero) === 11) {
        // Formato: (XX) 9XXXX-XXXX
        return sprintf('(%s) %s-%s',
            substr($numero, 0, 2),    // DDD
            substr($numero, 2, 5),    // primeiros 5 dígitos
            substr($numero, 7)        // últimos 4 dígitos
        );
    } elseif (strlen($numero) === 10) {
        // Formato: (XX) XXXX-XXXX
        return sprintf('(%s) %s-%s',
            substr($numero, 0, 2),
            substr($numero, 2, 4),
            substr($numero, 6)
        );
    }

    // Se não tiver 10 ou 11 dígitos, retorna o número original
    return $numero;
}

?>