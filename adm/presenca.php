<?php
$titulo = "Presenças";
require_once __DIR__ . "/../includes/inicio.php";

// puxando todos os eventos
$sql = "SELECT * FROM eventos WHERE status = 0";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();

// placeholder img
$link_placeholder = 'https://blog.iprocess.com.br/wp-content/uploads/2021/11/placeholder.png';
?>
<main>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="flex gap-2 text-2xl font-bold items-center mb-2 text-texto-preto">
                Controle de Presenças
                <i class="bi bi-dash text-blue-600 text-lg"></i>
                <span
                    class="font-normal text-blue-800 text-sm flex items-center gap-1 bg-blue-100 rounded-full px-3 py-0.5">
                    <?= htmlspecialchars($resultado->num_rows) ?> Eventos cadastrados
                </span>
            </h2>
            <span class="font-normal text-xl text-texto-opaco" id="data_atual"></span>
        </div>
    </div>
    <?php
    if ($resultado->num_rows > 0) : ?>
    <!--container cards eventos-->
    <div class="grid grid-cols-3 gap-5 mt-5">
        <?php while ($row = $resultado->fetch_assoc()) : ?>
        <!--card eventos-->
        <a href="<?= BASE_URL . "adm/presenca/lista?id=" . htmlspecialchars($row["id"])?>">
            <div class="card-eventos hover:scale-103 h-full">
                <div class="img-container">
                    <img src="<?= htmlspecialchars($row['link_img'] ?: $link_placeholder) ?>"
                        alt="Imagem do evento <?= htmlspecialchars($row['nome']) ?>"
                        onerror="this.onerror=null; this.src='<?= $link_placeholder ?>';">
                    <span class="tag-status bg-blue-100 text-blue-600">
                        Publicado
                    </span>
                    <span class="tag-tipo">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </span>
                </div>
                <div class="txt-card">
                    <h2><?= htmlspecialchars($row['nome']) ?></h2>
                    <p class="descricao">
                        <?= htmlspecialchars($row['descricao']) ?>
                    </p>
                    <?php
                        // puxando quantos voluntarios tem nesse eventos
                        $sql = "SELECT COUNT(*) FROM presencas WHERE id_evento = ?";
                        $stmt = $conexao->prepare($sql);
                        $stmt->bind_param("i", $row['id']);
                        $stmt->execute();
                        $stmt->bind_result($presencas);
                        $stmt->fetch();
                        $stmt->close();
                        $meta = (int)$row['meta_voluntarios'];
                        $inscritos = (int)$presencas;
                        $percentual = $meta > 0 ? round(($inscritos / $meta) * 100) : 0;
                    ?>
                    <div class="mt-5">
                        <p class="flex w-full items-center justify-between">
                            Inscrições
                            <span><?= $percentual ?>%</span>
                        </p>
                        <div class="container-barra">
                            <div class="barra" style="width: <?= $percentual ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <?php endwhile; ?>
    </div>
    <?php else : ?>
    <div>
        <h3>Nenhum evento encontrado</h3>
    </div>
    <?php endif; ?>
</main>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>