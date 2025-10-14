<?php
$titulo = "Presenças";
require_once __DIR__ . "/../includes/inicio.php";

$id = (int)($_GET["id"] ?? 0);

$sql = "SELECT id, nome, data_hora FROM eventos WHERE id = ? AND STATUS = 0 LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($id_evento, $nome_evento, $data_hora);

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
        Controle de Presenças
        <span id="data_atual"></span>
    </h2>
    <?php 
        $sql = "SELECT COUNT(id_evento) AS inscritos, (SELECT COUNT(id_voluntario) FROM presencas WHERE status = 1 AND id_evento = ?) AS presentes FROM presencas WHERE id_evento = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $id_evento, $id_evento);
        $stmt->execute();
        $stmt->bind_result($inscritos, $presentes);
        $stmt->fetch();
        $stmt->close(); 
    ?>
    <div class="flex items-center justify-between border border-borda rounded-lg shadow-lg bg-white px-5 py-3">
        <div class="flex items-baseline flex-col gap-1">
            <h2 class="text-texto-preto text-xl font-bold"><?= htmlspecialchars($nome_evento) ?></h2>
            <p class="text-texto-opaco text-md" id="data_hora_evento"><?= htmlspecialchars($data_hora) ?></p>
            <p class="flex gap-2 text-texto-opaco text-sm">Inscritos: <?= htmlspecialchars($inscritos) ?><span
                    class="text-green-600">Presentes:
                    <?= htmlspecialchars($presentes) ?></span>
            </p>
        </div>
        <button class="btn-novo">
            <i class="bi bi-download"></i> Exportar lista
        </button>
    </div>
    <!-- <input type="search" name="" id="" placeholder="Buscar voluntário..." class="pesquisa"></input> -->
    <div class="mt-5 border border-borda rounded-lg shadow-lg bg-white">
        <p class="border-b border-borda px-5 py-3 font-semibold text-texto-preto text-lg">Voluntários</p>
        <?php
            // puxando voluntários do evento
            $sql = "SELECT v.id, v.nome, v.email, v.telefone, p.status FROM presencas p INNER JOIN voluntarios v ON v.id = p.id_voluntario WHERE p.id_evento = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $id_evento);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) :
                while ($row = $resultado->fetch_assoc()):
        ?>
        <div class="flex items-center justify-between px-5 py-3">
            <div class="flex justify-start items-center gap-4">
                <span
                    class="bg-gradient-to-r from-azul to-verde rounded-full w-12 h-12 text-stone-100 flex justify-center items-center text-2xl font-bold"><?= htmlspecialchars(substr($row["nome"], 0, 1)) ?></span>
                <div>
                    <h3 class="text-lg font-bold text-texto-preto"><?= htmlspecialchars($row["nome"]) ?></h3>
                    <p class="text-base text-texto-opaco"><?= htmlspecialchars($row["email"]) ?></p>
                    <p class="text-base text-texto-opaco"><?= htmlspecialchars($row["telefone"]) ?></p>
                </div>
            </div>
            <div class="flex justify-center items-center gap-1">
                <!-- Card voluntário presente ou ausente -->
                <span
                    class="<?= ($row["status"] == 0) ? "bg-stone-100 text-stone-600" : "bg-green-100 text-green-600" ?> rounded-xl px-2 text-sm"><?= ($row["status"] == 0) ? "Ausente" : "Presente" ?></span>
                <!--Presente-->
                <form action="<?= BASE_URL . "adm/alterar_presenca"?>" method="POST">
                    <!--csrf-->
                    <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                    <input type="hidden" name="id_voluntario" id="id_voluntario"
                        value="<?= htmlspecialchars($row['id']) ?>">
                    <input type="hidden" name="id_evento" id="id_evento" value="<?= htmlspecialchars($id_evento) ?>">
                    <input type="hidden" name="status" id="status" value="1">

                    <button
                        class="w-10 h-10 rounded-lg hover:bg-green-100 text-green-500 cursor-pointer <?= ($row["status"] == 1) ? "bg-green-100" : ""?>">
                        <i class="bi bi-check2"></i>
                    </button>
                </form>
                <!--Ausente-->
                <form action="<?= BASE_URL . "adm/alterar_presenca"?>" method="POST">
                    <!--csrf-->
                    <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                    <input type="hidden" name="id_voluntario" id="id_voluntario"
                        value="<?= htmlspecialchars($row['id']) ?>">
                    <input type="hidden" name="id_evento" id="id_evento" value="<?= htmlspecialchars($id_evento) ?>">
                    <input type="hidden" name="status" id="status" value="0">

                    <button
                        class="w-10 h-10 rounded-lg hover:bg-red-100 text-red-500 cursor-pointer <?= ($row["status"] == 0) ? "bg-red-100" : ""?>"
                        type="submit">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
        <div class="flex items-center justify-between px-5 py-3">
            <p>Nenhum voluntário cadastrado!</p>
        </div>
        <?php endif; ?>
    </div>
    <script>
    function formatDate(dateString) {
        const date = new Date(dateString);

        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Meses começam de 0
        const year = date.getFullYear();

        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');

        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }

    // Formatar a data do evento
    const dataHoraEvento = document.getElementById('data_hora_evento').innerText;
    document.getElementById('data_hora_evento').innerText = formatDate(dataHoraEvento);
    </script>
</main>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>