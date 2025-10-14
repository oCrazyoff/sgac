<?php
$titulo = "Presenças";
require_once __DIR__ . "/../includes/inicio.php";

$id = (int)($_GET["id"] ?? 0);

$sql = "SELECT id, nome FROM eventos WHERE id = ? AND STATUS = 0 LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($id_evento, $nome_evento);

if (!$stmt->fetch()) {
    // Nenhum evento encontrado
    $_SESSION["resposta"] = "Evento não encontrado!";
    header("Location:" . BASE_URL . "adm/presenca");
    exit();
}

$stmt->close();
?>
    <main>
        <h2 class="titulo">
            <?= htmlspecialchars($nome_evento) ?>
            <span id="data_atual"></span>
        </h2>
        <div>
            <?php
            // puxando voluntários do evento
            $sql = "SELECT id_voluntario FROM presencas WHERE id_evento = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $id_evento);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) :
                while ($row = $resultado->fetch_assoc()) :
                    ?>
                    <div>
                        <?php
                        // puxando o nome do usuario
                        $sql = "SELECT nome FROM voluntarios WHERE id = ?";
                        $stmt = $conexao->prepare($sql);
                        $stmt->bind_param("i", $row["id_voluntario"]);
                        $stmt->execute();
                        $stmt->bind_result($nome_voluntario);
                        $stmt->fetch();
                        $stmt->close();
                        echo $nome_voluntario;
                        ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </main>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>