<?php
function voluntarios_cadastrados() {
    global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT COUNT(id) FROM voluntarios");
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $stmt->bind_result($quantidade_voluntarios);
        $stmt->fetch();
        $stmt->close();
        return (int) ($quantidade_voluntarios ?? 0);

    } catch (Exception $erro) {
        return ("Erro em voluntarios_cadastrados(): " . $erro->getMessage());
    }
}

function eventos_cadastrados() {
    global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT COUNT(id) FROM eventos");
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $stmt->bind_result($quantidade_eventos);
        $stmt->fetch();
        $stmt->close();
        return (int) ($quantidade_eventos ?? 0);

    } catch (Exception $erro) {
        return ("Erro em eventos_cadastrados(): " . $erro->getMessage());
    }
}

function doacoes_recebidas() {
    global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT COUNT(id) FROM doacoes");
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $stmt->bind_result($quantidade_doacoes);
        $stmt->fetch();
        $stmt->close();
        return (int) ($quantidade_doacoes ?? 0);

    } catch (Exception $erro) {
        return ("Erro em eventos_cadastrados(): " . $erro->getMessage());
    }
}

function voluntario_ativo() {
    global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT (SELECT nome FROM voluntarios WHERE id = id_voluntario) AS nome, COUNT(id_voluntario) AS quantidade FROM presencas GROUP BY nome ORDER BY quantidade DESC LIMIT 1");
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }

        $stmt->bind_result($nome, $quantidade);
        $stmt->fetch();
        $stmt->close();
        return $nome;

    } catch (Exception $erro) {
        return ("Erro em eventos_cadastrados(): " . $erro->getMessage());
    }
}

function eventos_tipos() {
    global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT tipo, COUNT(tipo) AS quantidade FROM eventos GROUP BY tipo");
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }
        
        $resultado = $stmt->get_result();
        $tipos = [];

        while($row = $resultado->fetch_assoc()){
            $tipos[] = $row; // Adiciona cada resultado ao array
        }
        
        $stmt->close();
        return $tipos; // Retorna array com todos os tipos
        
        $stmt->close(); 

    } catch (Exception $erro) {
        return ("Erro em eventos_cadastrados(): " . $erro->getMessage());
    }
}

function formatar_mes($mes){
    switch ($mes) {
        case 1:
            return "Janeiro";
            break;
        case 2:
            return "Fevereiro";
            break;
        case 3:
            return "Março";
            break;
        case 4:
            return "Abril";
            break;
        case 5:
            return "Maio";
            break;
        case 6:
            return "Junho";
            break;
        case 7:
            return "Julho";
            break;
        case 8:
            return "Agosto";
            break;
        case 9:
            return "Setembro";
            break;
        case 10:
            return "Outubro";
            break;
        case 11:
            return "Novembro";
            break;
        case 12:
            return "Dezembro";
            break;
        default:
            return "Mês não cadastrado!";
            break;
    }
}

function eventos_data(){
        global $conexao;

    try {
        $stmt = $conexao->prepare("SELECT DATE_FORMAT(data_hora, '%m') as mes_ano, COUNT(*) as total FROM eventos GROUP BY DATE_FORMAT(data_hora, '%m') ORDER BY mes_ano DESC");
        if (!$stmt) {
        throw new Exception("Erro ao preparar a consulta: " . $conexao->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a consulta: " . $stmt->error);
        }
        
        $resultado = $stmt->get_result();
        $datas = [];

        while($row = $resultado->fetch_assoc()){
            $datas[] = $row; // Adiciona cada resultado ao array
        }
        
        $stmt->close();
        return $datas; // Retorna array com todos os tipos
        
        $stmt->close(); 

    } catch (Exception $erro) {
        return ("Erro em eventos_cadastrados(): " . $erro->getMessage());
    }
}

?>