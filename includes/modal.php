<!--modal-->
<div id="modal" class="hidden">
    <div id="form-container">
        <h2 id="modal-title" class="text-xl font-bold mb-4"></h2>
        <form id="modal-form" action="#" method="POST">
            <!--CSRF-->
            <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
            <?php
            if (isset($tipo_modal)):

                // modal de voluntários
                if ($tipo_modal == 'voluntarios'): ?>

                    <!--conteudo do formulario-->
                    <label for="nome">Nome Completo</label>
                    <input type="text" name="nome" id="nome" class="input-modal" placeholder="Digite o nome completo"
                           required>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="input-modal" placeholder="Digite o email">
                    <label for="telefone">Telefone</label>
                    <input type="number" name="telefone" id="telefone" class="input-modal"
                           placeholder="(11) 99999-9999">
                    <label for="habilidades">Habilidades</label>
                    <input type="text" name="habilidades" id="habilidades" class="input-modal"
                           placeholder="Cozinhar, Comunicação etc...">

                <?php elseif

                    // modal de eventos (falta fazer)
                ($tipo_modal == 'eventos'): ?>

                    <!--conteudo formulário-->
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="input-modal" placeholder="Nome da categoria"
                           required>
                <?php endif; ?>
            <?php endif; ?>
            <div class="grid grid-cols-3 gap-2 mt-5">
                <button type="submit" class="btn-submit">Enviar</button>
                <button type="button" class="btn-cancelar" onclick="fecharModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    // funções do modal

    // Capitaliza a primeira letra
    function capitalizarPrimeiraLetra(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function abrirCadastrarModal(tabela) {
        const modal = document.getElementById('modal');
        const form = document.getElementById('modal-form');

        modal.classList.remove('hidden');
        document.getElementById('modal-title').textContent = `Cadastrar ${capitalizarPrimeiraLetra(tabela)}`;

        // limpa campos do form
        form.reset();

        // altera action do form para o PHP de cadastro
        form.action = `cadastrar_${tabela}`;
    }

    async function abrirEditarModal(tabela, id) {
        const modal = document.getElementById('modal');
        const form = document.getElementById('modal-form');
        const modalTitle = document.getElementById('modal-title');

        // Mostrar modal imediatamente
        modal.classList.remove('hidden');

        // Coloca título temporário
        modalTitle.textContent = "Carregando...";

        // Altera action do form
        form.action = `editar_${tabela}?id=${id}`;

        try {
            // Busca os dados
            const resp = await fetch(`../buscar_${tabela}?id=${id}`);
            const dados = await resp.json();

            // Preenche os campos do form
            for (const campo in dados) {
                if (form[campo]) form[campo].value = dados[campo];
            }

            // Atualiza título com o correto
            modalTitle.textContent = `Editar ${capitalizarPrimeiraLetra(tabela)}`;
        } catch (erro) {
            modalTitle.textContent = `Erro ao carregar ${capitalizarPrimeiraLetra(tabela)}`;
            console.error("Erro ao buscar dados:", erro);
        }
    }

    function fecharModal(tabela) {
        document.getElementById(`modal`).classList.add('hidden');
    }
</script>