<?php
$titulo = "Voluntários";
require_once __DIR__ . "/../includes/inicio.php";

// puxando todos os voluntarios
$sql = "SELECT id, nome, email, telefone, habilidades FROM voluntarios";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
?>
<main>
    <h2 class="titulo">
        Voluntários
        <span id="data_atual"></span>
    </h2>
    <div class="subtitulo">
        <h3>
            Voluntários Cadastrados
            <span><?= htmlspecialchars($resultado->num_rows) ?> voluntários no total</span>
        </h3>
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
                <div class="border border-borda rounded-lg p-10 text-xl shadow-lg">
                    <h3><?= htmlspecialchars($row['nome']) ?></h3>
                    <p><?= htmlspecialchars($row['email']) ?></p>
                    <div>
                        <?= htmlspecialchars($row['habilidades']) ?>
                    </div>

                    <!--opções do card-->
                    <div>
                        <!--editar-->
                        <button onclick="abrirEditarModal('voluntarios', <?= htmlspecialchars($row['id']) ?>)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <!--deletar-->
                        <form action="deletar_voluntario" method="POST">
                            <!--csrf-->
                            <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                            <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($row['id']) ?>">

                            <button type="submit"><i class="bi bi-trash3"></i></button>
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
