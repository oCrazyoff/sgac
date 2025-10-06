<?php
$titulo = "Eventos";
require_once __DIR__ . "/../includes/inicio.php";
require_once __DIR__ . "/../backend/dashboard/funcoes.php";
?>
<main>
    <h2 class="titulo">
        Relatórios
        <span id="data_atual"></span>
    </h2>
    <p>Voluntários cadastrados: <?= htmlspecialchars(voluntarios_cadastrados()) ?></p>
    <p>Eventos cadastrados: <?= htmlspecialchars(eventos_cadastrados()) ?></p>
    <p>Doações recebidas: <?= htmlspecialchars(doacoes_recebidas()) ?></p>
    <p>Voluntário mais ativo: <?= htmlspecialchars(voluntario_ativo()) ?></p>
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