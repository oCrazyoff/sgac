<?php
require_once __DIR__ . '/../valida.php';
header('Content-Type: application/json');

// Pega o ID via GET e valida
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode(['erro' => 'ID inválido']);
    exit;
}

// Prepara e executa a consulta
$stmt = $conexao->prepare("SELECT id_evento, status, prioridade, meta_valor FROM doacoes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se encontrou o evento
if ($resultado->num_rows === 0) {
    echo json_encode(['erro' => 'Evento não encontrado']);
    exit;
}

// Retorna o evento como JSON
$row = $resultado->fetch_assoc();
echo json_encode($row);

$stmt->close();