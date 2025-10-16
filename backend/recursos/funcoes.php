<?php
function total_recursos() {
    global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT COUNT(id) FROM doacoes");
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $stmt->bind_result($total_recursos);
        $stmt->fetch();
        $stmt->close();
        return (int) ($total_recursos ?? 0);

    } catch (Exception $erro) {
        return ("Erro em total_recursos(): " . $erro->getMessage());
    }
}

function total_arrecadado() {
    global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT SUM(valor) AS total_arrecadado FROM itens_doacao");
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $stmt->bind_result($total_arrecadado);
        $stmt->fetch();
        $stmt->close();
        return (int) ($total_arrecadado ?? 0);

    } catch (Exception $erro) {
        return ("Erro em total_arrecadado(): " . $erro->getMessage());
    }
}

function metas_atingidas() {
    global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT COUNT(*) AS metas_atingidas FROM (SELECT d.id FROM doacoes d LEFT JOIN itens_doacao i ON i.id_doacao = d.id GROUP BY d.id, d.meta_valor HAVING COALESCE(SUM(i.valor), 0) >= d.meta_valor) AS metas");
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $stmt->bind_result($metas_atingidas);
        $stmt->fetch();
        $stmt->close();
        return (int) ($metas_atingidas ?? 0);

    } catch (Exception $erro) {
        return ("Erro em total_arrecadado(): " . $erro->getMessage());
    }
}

function mostrar_prioridade($prioridade){
    switch ($prioridade) {
        case '0':
            return("Baixa");
            break;
        case '2':
            return("Média");
            break;
        case '3':
            return("Alta");
            break;                            
        default:
            return("N/a");
            break;
    }
}

function metas($id) {
    global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT SUM(valor) AS valor FROM `itens_doacao` WHERE id_doacao = ?");
        $stmt->bind_param("i", $id);
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $stmt->bind_result($valor);
        $stmt->fetch();
        $stmt->close();
        return (int) ($valor ?? 0);

    } catch (Exception $erro) {
        return ("Erro em total_arrecadado(): " . $erro->getMessage());
    }
}