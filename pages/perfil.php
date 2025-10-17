<?php
$titulo = "Perfil";
require_once __DIR__ . "/../includes/inicio.php";

$id_voluntario = (int)($_GET["id"] ?? 0);

$sql = "SELECT id FROM voluntarios WHERE id = ? LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_voluntario);
$stmt->execute();
$stmt->bind_result($id);

if (!$stmt->fetch()) {
    // Nenhum evento encontrado
    $_SESSION["resposta"] = "Voluntário não cadastrado!";
    header("Location:" . BASE_URL . "adm/eventos");
    exit();
}

$stmt->close();

$sql = "SELECT nome, email, telefone, habilidades FROM voluntarios WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_voluntario);
$stmt->execute();
$stmt->bind_result($nome, $email, $telefone, $habilidades);
$stmt->fetch();
$stmt->close();
?>
<main>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="flex gap-2 text-2xl font-bold items-center mb-2 text-texto-preto">
                Meu Perfil
            </h2>
            <span class="font-normal text-xl text-texto-opaco" id="data_atual"></span>
        </div>
        <button class="btn-novo" onclick="abrirEditarModal('eventos')">
            <i class="bi bi-pencil-square"></i> Editar Perfil
        </button>
    </div>
    <div class="grid grid-cols-3 gap-5 mt-5">
        <div class="w-full row-span-2 h-full card-perfil">
            <div class="flex justify-start items-center gap-4">
                <span
                    class="bg-gradient-to-r from-azul to-verde rounded-full w-12 h-12 text-stone-100 flex justify-center items-center text-2xl font-bold"><?= htmlspecialchars(substr($nome, 0, 1)) ?></span>
                <div>
                    <h3 class="text-lg font-bold text-texto-preto"><?= htmlspecialchars($nome) ?></h3>
                    <p class="text-base text-texto-opaco"><?= htmlspecialchars($row['email']) ?></p>
                </div>
            </div>
        </div>
        <div class="w-full h-50 col-span-2 card-perfil"></div>
        <div class="w-full h-50 card-perfil"></div>
        <div class="w-full h-50 card-perfil"></div>
    </div>

    <div class="flex justify-start items-center gap-2 flex-wrap">
        <?php
                                $habilidades = explode(",", $row['habilidades']);
                                for ($i = 0; $i < count($habilidades); $i++):
                                    ?>
        <span class="bg-blue-100 rounded-xl px-3 text-blue-600 text-sm"><?= htmlspecialchars($habilidades[$i]) ?></span>
        <?php endfor; ?>
    </div>
</main>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>