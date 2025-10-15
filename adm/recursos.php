<?php
$titulo = "Recursos";
require_once __DIR__ . "/../includes/inicio.php";
require_once __DIR__ . "/../backend/recursos/funcoes.php";
?>
<main>
    <h2 class="titulo">
        Recursos e Doações
        <span id="data_atual"></span>
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-5">
        <div class="border border-borda rounded-lg p-5 text-xl shadow-lg flex justify-between items-center">
            <div class="flex items-start w-full flex-col">
                <p class="text-base text-texto-opaco">Voluntários Cadastrados</p>
                <span class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars(total_recursos()) ?></span>
            </div>
            <span class="bg-blue-100 rounded-lg w-15 h-13 text-blue-600 flex justify-center items-center text-2xl"><i
                    class="bi bi-bag-heart"></i></span>
        </div>
        <div class="border border-borda rounded-lg p-5 text-xl shadow-lg flex justify-between items-center">
            <div class="flex items-start w-full flex-col">
                <p class="text-base text-texto-opaco">Total Arrecadado</p>
                <span class="text-2xl font-bold text-texto-preto">R$ <?= htmlspecialchars(total_arrecadado()) ?></span>
            </div>
            <span class="bg-green-100 rounded-lg w-15 h-13 text-green-600 flex justify-center items-center text-2xl"><i
                    class="bi bi-currency-dollar"></i></span>
        </div>
        <div class="border border-borda rounded-lg p-5 text-xl shadow-lg flex justify-between items-center">
            <div class="flex items-start w-full flex-col">
                <p class="text-base text-texto-opaco">Metas Atingidas</p>
                <span class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars(metas_atingidas()) ?></span>
            </div>
            <span class="bg-green-100 rounded-lg w-15 h-13 text-green-600 flex justify-center items-center text-2xl"><i
                    class="bi bi-check2"></i></span>
        </div>
    </div>
    <div class="mt-5 border border-borda rounded-lg shadow-lg bg-white">
        <p class="border-b border-borda px-5 py-3 font-semibold text-texto-preto text-lg">Recursos Necessários</p>
        <?php
            // puxando voluntários do evento
            $sql = "SELECT d.id, e.nome, d.meta_valor, d.status, d.prioridade FROM doacoes d INNER JOIN eventos e ON d.id_evento = e.id";
            $stmt = $conexao->prepare($sql);
            // $stmt->bind_param("i", $id_evento);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) :
                while ($row = $resultado->fetch_assoc()):
        ?>
        <div class="flex items-center justify-between px-5 py-3 border-b border-borda">
            <div class="flex flex-col justify-start items-left gap-2">
                <h3 class="text-lg font-bold text-texto-preto flex justify-center items-center gap-3">
                    <?= htmlspecialchars($row["nome"]) ?><span
                        class="bg-blue-100 text-blue-600 font-normal rounded-xl px-2 text-sm"><?= ($row["status"] == 0) ? "Em andamento" : "Concluído" ?></span>
                </h3>
                <p class="text-base text-texto-opaco">Tipo: Financeiro <?= htmlspecialchars($row["prioridade"]) ?></p>
                <p class="text-base text-texto-opaco"></p>
            </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
        <div class="flex items-center justify-between px-5 py-3">
            <p>Nenhuma doação encontrada!</p>
        </div>
        <?php endif; ?>
    </div>
</main>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>