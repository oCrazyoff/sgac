<?php
$titulo = "Recursos";
require_once __DIR__ . "/../includes/inicio.php";
require_once __DIR__ . "/../backend/recursos/funcoes.php";
?>
    <div id="modal" class="hidden">
        <div id="form-container">
            <button type="button" class="btn-fechar-modal" onclick="fecharModal()">&times;</button>
            <h2 id="modal-title" class="text-xl font-bold mb-4">Doação</h2>
            <form id="modal-form" action="doar_valor" method="POST">
                <!--CSRF-->
                <input type="hidden" name="csrf" id="csrf"
                       value="<?= gerarCSRF() ?>">
                <input type="hidden" name="id_doacao" id="id_doacao">

                <!--conteudo formulário-->
                <label for="valor">Valor</label>
                <input type="number" name="valor" id="valor" class="input-modal"
                       placeholder="Digite o valor a ser doado"
                       required>

                <div class="grid grid-cols-2 gap-2 mt-5">
                    <button type="submit" class="btn-submit">
                        Enviar
                    </button>
                    <button type="button" class="btn-cancelar" onclick="fecharModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <main>
        <h2 class="titulo">
            Recursos e Doações
            <span id="data_atual"></span>
        </h2>
        <div class="mt-5 border border-borda rounded-lg shadow-lg bg-white overflow-hidden">
            <div class="border-b border-borda px-5 py-5 flex justify-between items-center">
                <p class="font-semibold text-texto-preto text-lg">Recursos Necessários</p>
            </div>

            <?php
            // puxando recursos em andamento
            $sql = "SELECT d.id, e.nome, d.meta_valor, d.status, d.prioridade 
                    FROM doacoes d
                    INNER JOIN eventos e ON d.id_evento = e.id
                    WHERE d.status = 0";
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
                        <div class="flex ml-10 justify-center items-center">
                            <!--doar-->
                            <button class="whitespace-nowrap px-5 py-2 bg-azul rounded-lg text-white cursor-pointer hover:bg-azul-hover"
                                    onclick="abrirModal(<?= htmlspecialchars($row['id']) ?>)">
                                <i class="bi bi-cash-stack"></i> Doar
                            </button>
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
    <script>
        // funções do modal

        // Helpers para animação
        function aplicarAnimacaoAbertura(overlayEl, contentEl) {
            // remove classes de saída e força reflow para reiniciar animação
            overlayEl.classList.remove('modal-anim-out');
            contentEl.classList.remove('modal-content-anim-out');
            // reflow
            void overlayEl.offsetWidth;
            overlayEl.classList.add('modal-anim-in');
            contentEl.classList.add('modal-content-anim-in');
        }

        function aplicarAnimacaoFechamento(overlayEl, contentEl, onFim) {
            // remove classes de entrada
            overlayEl.classList.remove('modal-anim-in');
            contentEl.classList.remove('modal-content-anim-in');
            // reflow
            void overlayEl.offsetWidth;
            // adiciona classes de saída
            overlayEl.classList.add('modal-anim-out');
            contentEl.classList.add('modal-content-anim-out');

            // após animação do conteúdo concluir, esconde e limpa
            const handleEnd = () => {
                contentEl.removeEventListener('animationend', handleEnd);
                overlayEl.classList.add('hidden');
                // limpeza
                overlayEl.classList.remove('modal-anim-out');
                contentEl.classList.remove('modal-content-anim-out');
                if (typeof onFim === 'function') onFim();
            };
            contentEl.addEventListener('animationend', handleEnd, {
                once: true
            });
        }

        function abrirModal(id) {
            const modal = document.getElementById('modal');
            const form = document.getElementById('modal-form');
            const content = document.getElementById('form-container');

            //trocando o id do evento
            form.querySelector('#id_doacao').value = id;
            modal.classList.remove('hidden');

            // aplica animação de abertura
            aplicarAnimacaoAbertura(modal, content);
        }

        function fecharModal() {
            const modal = document.getElementById('modal');
            const content = document.getElementById('form-container');
            aplicarAnimacaoFechamento(modal, content);
        }

        // Fechar com ESC e clique no backdrop
        (function initModalListeners() {
            const modal = document.getElementById('modal');
            const content = document.getElementById('form-container');
            if (!modal || !content) return;

            // Clique no backdrop
            modal.addEventListener('click', (ev) => {
                if (ev.target === modal) {
                    fecharModal();
                }
            });

            // Tecla ESC
            document.addEventListener('keydown', (ev) => {
                if (ev.key === 'Escape' && !modal.classList.contains('hidden')) {
                    fecharModal();
                }
            });
        })();
    </script>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>