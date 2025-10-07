<?php
$titulo = "Relatórios";
require_once __DIR__ . "/../includes/inicio.php";
require_once __DIR__ . "/../backend/dashboard/funcoes.php";

// puxando todos os voluntarios
$sql = "SELECT id, nome, email, telefone, habilidades FROM voluntarios";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();
?>
<main>
    <h1 class="titulo">
        Relatórios
        <span id="data_atual"></span>
    </h1>
    <div class="grid grid-cols-4 gap-5 mt-5">
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
    </div>
    <p>
        <?php 
    $datas = eventos_data();
    
    // Verificar se não é uma mensagem de erro
    if (is_array($datas)) {
        foreach($datas as $data) {
            echo htmlspecialchars(formatar_mes($data["mes_ano"])) . " - ";
            echo htmlspecialchars($data["total"]) . "<br>";
        }
    } else {
        // Exibir erro se houver
        echo htmlspecialchars($datas);
    }
    ?>
    </p>
    <p>
        <?php 
    $tipos = eventos_tipos();
    
    // Verificar se não é uma mensagem de erro
    if (is_array($tipos)) {
        foreach($tipos as $tipo) {
            echo "Tipo: " . htmlspecialchars($tipo["tipo"]) . " - ";
            echo "Quantidade: " . htmlspecialchars($tipo["quantidade"]) . "<br>";
        }
    } else {
        // Exibir erro se houver
        echo htmlspecialchars($tipos);
    }
    ?>
    </p>

</main>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>