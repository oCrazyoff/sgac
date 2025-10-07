<?php
$titulo = "Voluntários";
require_once __DIR__ . "/../includes/inicio.php";

// puxando todos os voluntarios
$sql = "SELECT v.id, v.nome, v.email, v.telefone, v.habilidades, COALESCE(p.presencas, 0) AS presencas FROM voluntarios v LEFT JOIN (SELECT id_voluntario, COUNT(*) as presencas FROM presencas GROUP BY id_voluntario) p ON v.id = p.id_voluntario";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
?>
<main>
    <h1 class="titulo">
        Voluntários
        <span id="data_atual"></span>
    </h1>
    <div class="subtitulo">
        <h2>
            Voluntários Cadastrados
            <span><?= htmlspecialchars($resultado->num_rows) ?> voluntários no total</span>
        </h2>
        <button onclick="abrirCadastrarModal('voluntarios')">
            <i class="bi bi-plus"></i> Novo Voluntário
        </button>
    </div>
    <?php
    if ($resultado->num_rows > 0) : ?>
    <!--container cards voluntarios-->
    <div class="grid grid-cols-3 gap-5 mt-5">
        <?php while ($row = $resultado->fetch_assoc()) : ?>
        <!--card voluntários-->
        <div class="card">
            <div class="flex justify-start items-center gap-4">
                <span
                    class="bg-gradient-to-r from-azul to-verde rounded-full w-12 h-12 text-stone-100 flex justify-center items-center text-2xl font-bold"><?= htmlspecialchars(substr($row['nome'], 0, 1)) ?></span>
                <div>
                    <h3 class="text-xl font-bold text-texto-preto"><?= htmlspecialchars($row['nome']) ?></h3>
                    <p class="text-lg text-texto-opaco"><?= htmlspecialchars($row['email']) ?></p>
                </div>
            </div>
            <div class="flex flex-col justify-start items-start gap-1">
                <h3 class="text-base text-texto-opaco font-normal">HABILIDADES</h3>
                <div class="flex justify-start items-center gap-2">
                    <?php 
                    $habilidades = explode(",", $row['habilidades']);
                    for($i = 0;$i < count($habilidades); $i++):
                ?>
                    <span
                        class="bg-blue-100 rounded-xl px-3 text-blue-600 text-base"><?= htmlspecialchars($habilidades[$i]) ?></span>
                    <?php endfor;?>
                </div>
            </div>
            <div class="flex flex-col justify-start items-start gap-1">
                <h3 class="text-base text-texto-opaco font-normal">PARTICIPAÇÕES</h3>
                <span class="text-texto-preto text-base"><?= htmlspecialchars($row["presencas"]) ?> Eventos</span>
            </div>

            <!--opções do card-->
            <div class="flex items-center justify-end gap-4 border-t border-stone-300 pt-3">
                <!--editar-->
                <button class="w-10 h-10 rounded-lg hover:bg-blue-100 text-blue-500"
                    onclick="abrirEditarModal('voluntarios', <?= htmlspecialchars($row['id']) ?>)">
                    <i class="bi bi-pencil"></i>
                </button>
                <!--deletar-->
                <form action="deletar_voluntarios" method="POST">
                    <!--csrf-->
                    <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                    <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($row['id']) ?>">

                    <button class="w-10 h-10 rounded-lg hover:bg-red-100 text-red-500" type="submit"><i
                            class="bi bi-trash3"></i></button>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else : ?>
    <div>
        <h3>Nenhum voluntario encontrado</h3>
    </div>
    <?php endif; ?>
</main>
<?php $tipo_modal = "voluntarios"; ?>
<?php require_once __DIR__ . "/../includes/modal.php"; ?>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>