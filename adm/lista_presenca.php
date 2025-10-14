<?php
$titulo = "Presenças";
require_once __DIR__ . "/../includes/inicio.php";

$id = (int) $_GET["id"];

$sql = "SELECT id FROM eventos WHERE id = ? LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows <= 0){
    $id = null;
}

if(!isset($id) && ($id == null)){
    $_SESSION["resposta"] = "Evento não encontrado!";
    header("Location:" . BASE_URL . "adm/presenca");
    exit();
}
?>
<main>
    <h2 class="titulo">
        Doações
        <span id="data_atual"></span>
    </h2>
</main>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>