<?php
$titulo = "Eventos";
require_once __DIR__ . "/../includes/inicio.php";

// puxando todos os eventos publicados
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
                    Eventos
                    <i class="bi bi-dash text-blue-600 text-lg"></i>
                    <span class="font-normal text-blue-800 text-sm flex items-center gap-1 bg-blue-100 rounded-full px-3 py-0.5">
                    <?= htmlspecialchars($resultado->num_rows) ?> Cadastrados
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
                    <div class="card-eventos">
                        <div class="img-container">
                            <img
                                    src="<?= htmlspecialchars($row['link_img'] ?: $link_placeholder) ?>"
                                    alt="Imagem do evento <?= htmlspecialchars($row['nome']) ?>"
                                    onerror="this.onerror=null; this.src='<?= $link_placeholder ?>';">
                            <?php if ($row['status'] == 0): ?>
                                <span class="tag-status bg-blue-100 text-blue-600">
                                Publicado
                            </span>
                            <?php elseif ($row['status'] == 1): ?>
                                <span class="tag-status bg-green-100 text-green-600">
                                Concluido
                            </span>
                            <?php elseif ($row['status'] == 2): ?>
                                <span class="tag-status bg-red-100 text-red-600">
                                Cancelado
                            </span>
                            <?php endif; ?>
                            <span class="tag-tipo">
                            <?= htmlspecialchars($row['tipo']) ?>
                        </span>
                        </div>
                        <div class="txt-card">
                            <h2><?= htmlspecialchars($row['nome']) ?></h2>
                            <p class="descricao">
                                <?= htmlspecialchars($row['descricao']) ?>
                            </p>
                            <p>
                                <i class="bi bi-calendar"></i>
                                <span id="data-hora">
                                <?= htmlspecialchars($row['data_hora']) ?>
                            </span>
                            </p>
                            <p>
                                <i class="bi bi-geo-alt"></i>
                                <?= htmlspecialchars($row['endereco']) ?>
                            </p>
                            <p>
                                <i class="bi bi-people"></i>
                                <span>
                                <?php
                                // puxando quantos voluntarios tem nesse eventos
                                $sql = "SELECT COUNT(*) FROM presencas WHERE id_evento = ?";
                                $stmt = $conexao->prepare($sql);
                                $stmt->bind_param("i", $row['id']);
                                $stmt->execute();
                                $stmt->bind_result($presencas);
                                $stmt->fetch();
                                $stmt->close();
                                ?>
                                    <?= htmlspecialchars($presencas) ?>/<?= htmlspecialchars($row['meta_voluntarios']) ?>
                                Voluntários
                            </span>
                            </p>
                            <?php
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
                                    <div class="barra"
                                         style="width: <?= $percentual ?>%;"></div>
                                </div>
                            </div>
                            <p class="mt-5">
                                <span class="text-black font-bold">Objetivos: </span>
                                <?= htmlspecialchars($row['objetivos']) ?>
                            </p>
                        </div>
                        <div class="w-full p-5 pt-0">
                            <form action="voluntariar_se" method="POST">
                                <!--csrf-->
                                <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                                <input type="hidden" name="id_evento" id="id_evento" value="<?= $row['id'] ?>">

                                <button class="w-full rounded-lg bg-azul text-white p-3 cursor-pointer hover:bg-azul-hover">
                                    Me Voluntariar
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div>
                <h3>Nenhum evento encontrado</h3>
            </div>
        <?php endif; ?>
    </main>
    <script>
        // formatando data e hora
        document.querySelectorAll('[id="data-hora"]').forEach(span => {
            const texto = span.textContent.trim();
            const data = new Date(texto.replace(" ", "T"));

            if (isNaN(data)) return; // evita erro se a data estiver inválida

            const formatador = new Intl.DateTimeFormat("pt-BR", {
                day: "numeric",
                month: "long",
                year: "numeric",
                hour: "2-digit",
                minute: "2-digit"
            });

            const partes = formatador.formatToParts(data);
            const resultado = `${partes.find(p => p.type === 'day').value} de ` +
                `${partes.find(p => p.type === 'month').value} de ` +
                `${partes.find(p => p.type === 'year').value} às ` +
                `${partes.find(p => p.type === 'hour').value}:${partes.find(p => p.type === 'minute').value}`;

            span.textContent = resultado;
        });
    </script>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>