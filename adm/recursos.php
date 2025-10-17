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
            <div class="card-recursos">
                <div class="flex items-start w-full flex-col">
                    <p class="text-base text-texto-opaco">Voluntários Cadastrados</p>
                    <span class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars(total_recursos()) ?></span>
                </div>
                <span class="bg-blue-100 rounded-lg w-15 h-13 text-blue-600 flex justify-center items-center text-2xl"><i
                            class="bi bi-bag-heart"></i></span>
            </div>
            <div class="card-recursos">
                <div class="flex items-start w-full flex-col">
                    <p class="text-base text-texto-opaco">Total Arrecadado</p>
                    <span class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars(formatarReal(total_arrecadado())) ?></span>
                </div>
                <span class="bg-green-100 rounded-lg w-15 h-13 text-green-600 flex justify-center items-center text-2xl"><i
                            class="bi bi-currency-dollar"></i></span>
            </div>
            <div class="card-recursos">
                <div class="flex items-start w-full flex-col">
                    <p class="text-base text-texto-opaco">Metas Atingidas</p>
                    <span class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars(metas_atingidas()) ?></span>
                </div>
                <span class="bg-green-100 rounded-lg w-15 h-13 text-green-600 flex justify-center items-center text-2xl"><i
                            class="bi bi-check2"></i></span>
            </div>
        </div>
        <div class="mt-5 border border-borda rounded-lg shadow-lg bg-white overflow-hidden">
            <div class="border-b border-borda px-5 py-5 flex justify-between items-center">
                <p class="font-semibold text-texto-preto text-lg">Recursos Necessários</p>
                <button class="btn-novo" onclick="abrirCadastrarModal('recursos')">
                    <i class="bi bi-plus"></i> Nova Doação
                </button>
            </div>

            <?php
            // puxando recursos
            $sql = "SELECT d.id, e.nome, d.meta_valor, d.status, d.prioridade FROM doacoes d INNER JOIN eventos e ON d.id_evento = e.id";
            $stmt = $conexao->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) :
                while ($row = $resultado->fetch_assoc()):
                    ?>
                    <div class="card-doacoes <?=
                    ($row['prioridade'] == 0) ? 'status-baixo' :
                        (($row['prioridade'] == 1) ? 'status-medio' : 'status-alto')
                    ?>">
                        <div class="flex flex-col justify-start items-left gap-2 w-full">
                            <h3 class="text-lg font-bold text-texto-preto flex justify-left items-center gap-3">
                                <?= htmlspecialchars($row["nome"]) ?>
                                <span class="bg-blue-100 text-blue-600 font-normal rounded-xl px-2 text-sm">
                                    <?= ($row["status"] == 0) ? "Em andamento" : "Concluído" ?>
                                </span>
                            </h3>
                            <p class="text-base text-texto-opaco flex gap-5">Tipo: Financeiro <span>Prioridade:
                        <?= htmlspecialchars(mostrar_prioridade($row["prioridade"])) ?></span></p>
                            <div class="w-full flex flex-col gap-2">
                                <div class="flex justify-between items-center">
                                    <p class="text-base text-texto-preto">Progresso</p>
                                    <p class="text-base text-texto-preto">
                                        <?= htmlspecialchars(formatarReal(metas($row["id"]))) ?>
                                        /
                                        <?= htmlspecialchars(formatarReal(($row["meta_valor"]))) ?>
                                    </p>
                                </div>
                                <?php
                                $meta = (int)$row['meta_valor'];
                                $arrecadado = (int)metas($row["id"]);
                                $percentual = $meta > 0 ? round(($arrecadado / $meta) * 100) : 0;
                                if ($percentual > 100) {
                                    $percentual = 100;
                                }
                                ?>
                                <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-azul to-verde h-full rounded-full"
                                         style="width: <?= $percentual ?>%;"></div>
                                </div>
                                <p class="text-sm text-texto-opaco"><?= $percentual ?>% Concluído</p>
                            </div>
                        </div>
                        <div class="flex flex-col w-15 justify-center items-center">
                            <!--editar-->
                            <button class="w-10 h-10 rounded-lg hover:bg-blue-100 text-blue-500 cursor-pointer"
                                    onclick="abrirEditarModal('recursos', <?= htmlspecialchars($row['id']) ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <!--deletar-->
                            <form action="deletar_recursos" method="POST">
                                <!--csrf-->
                                <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
                                <input type="hidden" name="id" id="id" value="<?= htmlspecialchars($row['id']) ?>">

                                <button class="w-10 h-10 rounded-lg hover:bg-red-100 text-red-500 cursor-pointer"
                                        type="submit">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="flex items-center justify-between px-5 py-3">
                    <p>Nenhuma doação encontrada!</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="mt-5 border border-borda rounded-lg shadow-lg bg-white">
            <p class="border-b border-borda px-5 py-5 flex justify-between items-center font-semibold text-texto-preto text-lg">
                Últimas Doações Recebidas
            </p>
            <?php
            // puxando voluntários do evento
            $sql = "SELECT nome_doador, numero_contato, data_doacao, valor FROM itens_doacao ORDER BY data_doacao ASC LIMIT 3";
            $stmt = $conexao->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0):
                while ($row = $resultado->fetch_assoc()):
                    ?>
                    <div class="flex items-center justify-between px-5 py-3 border-b border-borda">
                        <div class="flex flex-col justify-start items-left gap-1">
                            <h3 class="text-lg font-bold text-texto-preto flex justify-center items-center gap-2">
                                <?= htmlspecialchars($row["nome_doador"]) ?>
                                <span class="text-base font-normal text-texto-opaco">
                                    <?= htmlspecialchars(formatarData($row["data_doacao"])) ?>
                                </span>
                            </h3>
                            <p class="text-base text-texto-opaco">
                                <?= htmlspecialchars(formatarTelefone($row["numero_contato"])) ?>
                            </p>
                        </div>
                        <p class="font-bold text-green-600 text-lg"><?= htmlspecialchars(formatarReal($row["valor"])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="flex items-center justify-between px-5 py-3">
                    <p>Nenhuma doação encontrada!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
<?php $tipo_modal = "recursos"; ?>
<?php require_once __DIR__ . "/../includes/modal.php"; ?>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>