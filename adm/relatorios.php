<?php
$titulo = "Relatórios";
require_once __DIR__ . "/../includes/inicio.php";
require_once __DIR__ . "/../backend/dashboard/funcoes.php";

// puxando todos os voluntarios (código original mantido)
$sql = "SELECT id, nome, email, telefone, habilidades FROM voluntarios";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
?>
<main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <h2 class="titulo">
        Relatórios
        <span id="data_atual"></span>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-5">
        <div class="card">
            <div class="flex items-center justify-between w-full">
                <span
                    class="bg-blue-100 rounded-lg w-13 h-13 text-blue-600 flex justify-center items-center text-2xl"><i
                        class="bi bi-people"></i></span>
                <span class="text-green-600 text-base"><i class="bi bi-graph-up-arrow"></i> +12%</span>
            </div>
            <div class="flex items-start w-full flex-col">
                <span
                    class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars(voluntarios_cadastrados()) ?></span>
                <p class="text-base text-texto-opaco">Voluntários Cadastrados</p>
            </div>
        </div>
        <div class="card">
            <div class="flex items-center justify-between w-full">
                <span
                    class="bg-green-100 rounded-lg w-13 h-13 text-green-600 flex justify-center items-center text-2xl"><i
                        class="bi bi-calendar"></i></span>
                <span class="text-green-600 text-base"><i class="bi bi-graph-up-arrow"></i> +8%</span>
            </div>
            <div class="flex items-start w-full flex-col">
                <span class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars(eventos_cadastrados()) ?></span>
                <p class="text-base text-texto-opaco">Eventos Realizados</p>
            </div>
        </div>
        <div class="card">
            <div class="flex items-center justify-between w-full">
                <span class="bg-red-100 rounded-lg w-13 h-13 text-red-600 flex justify-center items-center text-2xl"><i
                        class="bi bi-bag-heart"></i></span>
                <span class="text-green-600 text-base"><i class="bi bi-graph-up-arrow"></i> +24%</span>
            </div>
            <div class="flex items-start w-full flex-col">
                <span class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars(doacoes_recebidas()) ?></span>
                <p class="text-base text-texto-opaco">Doações Recebidas</p>
            </div>
        </div>
        <div class="card">
            <div class="flex items-center justify-between w-full">
                <span
                    class="bg-purple-100 rounded-lg w-13 h-13 text-purple-600 flex justify-center items-center text-2xl"><i
                        class="bi bi-trophy"></i></span>
                <span class="text-green-600 text-base"><i class="bi bi-graph-up-arrow"></i> +7%</span>
            </div>
            <div class="flex items-start w-full flex-col">
                <span class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars(voluntario_ativo()) ?></span>
                <p class="text-base text-texto-opaco">Voluntário Mais Ativo</p>
            </div>
        </div>
        <div class="card col-span-2 h-96">
            <h3 class="text-lg font-semibold mb-4 text-texto-preto">Participação Mensal</h3>
            <div class="w-full h-70">
                <canvas id="graficoMensal" class="max-h-full"></canvas>
            </div>
        </div>
        <div class="card col-span-2 h-96">
            <h3 class="text-lg font-semibold mb-4 text-texto-preto">Tipos de Eventos</h3>
            <div class="w-full h-70">
                <canvas id="graficoTipos" class="max-h-full"></canvas>
            </div>
        </div>
    </div>

    <script>
    // Os dados são buscados pelas funções PHP que já foram incluídas no topo da página
    const dadosMensaisPHP = <?php echo eventos_data(); ?>;
    const dadosTiposPHP = <?php echo eventos_tipos(); ?>;

    // Revertemos para ter a ordem cronológica no gráfico (Janeiro, Fevereiro...)
    dadosMensaisPHP.reverse();

    const labelsMensal = dadosMensaisPHP.map(item => item.mes);
    const dataMensal = dadosMensaisPHP.map(item => item.total);

    const ctxMensal = document.getElementById('graficoMensal').getContext('2d');
    new Chart(ctxMensal, {
        type: 'bar',
        data: {
            labels: labelsMensal,
            datasets: [{
                label: 'Nº de Eventos',
                data: dataMensal,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)', // Azul Vibrante
                    'rgba(75, 192, 192, 0.8)', // Verde Água
                    'rgba(255, 206, 86, 0.8)', // Amarelo
                    'rgba(255, 99, 132, 0.8)', // Rosa/Vermelho
                    'rgba(153, 102, 255, 0.8)', // Roxo
                    'rgba(255, 159, 64, 0.8)', // Laranja
                    'rgba(10, 163, 129, 0.8)', // Verde Esmeralda
                    'rgba(231, 76, 60, 0.8)', // Vermelho Tomate
                    'rgba(52, 73, 94, 0.8)', // Azul Ardósia
                    'rgba(243, 156, 18, 0.8)', // Laranja Queimado
                    'rgba(142, 68, 173, 0.8)', // Roxo Ametista
                    'rgba(0, 230, 118, 0.8)' // Verde Claro Brilhante
                ],
            }]
        },
        options: {
            indexAxis: 'x',
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    const labelsTipos = dadosTiposPHP.map(item => item.tipo);
    const dataTipos = dadosTiposPHP.map(item => item.quantidade);

    const ctxTipos = document.getElementById('graficoTipos').getContext('2d');
    new Chart(ctxTipos, {
        type: 'pie',
        data: {
            labels: labelsTipos,
            datasets: [{
                label: 'Quantidade',
                data: dataTipos,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)', // Azul Vibrante
                    'rgba(75, 192, 192, 0.8)', // Verde Água
                    'rgba(255, 206, 86, 0.8)', // Amarelo
                    'rgba(255, 99, 132, 0.8)', // Rosa/Vermelho
                    'rgba(153, 102, 255, 0.8)', // Roxo
                    'rgba(255, 159, 64, 0.8)', // Laranja
                    'rgba(10, 163, 129, 0.8)', // Verde Esmeralda
                    'rgba(231, 76, 60, 0.8)', // Vermelho Tomate
                    'rgba(52, 73, 94, 0.8)', // Azul Ardósia
                    'rgba(243, 156, 18, 0.8)', // Laranja Queimado
                    'rgba(142, 68, 173, 0.8)', // Roxo Ametista
                    'rgba(0, 230, 118, 0.8)' // Verde Claro Brilhante
                ],
                hoverOffset: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
        }
    });
    </script>

</main>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>