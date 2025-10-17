<?php
$titulo = "Perfil";
require_once __DIR__ . "/../includes/inicio.php";
require_once __DIR__ . "/../backend/funcoes.php";

$id = trim(strip_tags($_SESSION["id"]));
$nome = trim(strip_tags($_SESSION["nome"]));
$email = trim(strip_tags($_SESSION["email"]));

try {
    // Tenta buscar como ADM
    $stmt = $conexao->prepare("SELECT id, nome, email FROM adm WHERE id = ? AND nome = ? AND email = ?");
    $stmt->bind_param("iss", $id, $nome, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();

    if($usuario){
        $cargo = 0; // 0 = ADM, 1 = Voluntário
    } else {
        $stmt = $conexao->prepare("SELECT id, nome, email, telefone, habilidades FROM voluntarios WHERE id = ? AND nome = ? AND email = ?");
        $stmt->bind_param("iss", $id, $nome, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        if ($usuario) {
            $cargo = 1; // Voluntário
        }
    }
    
    // Se não encontrou em nenhuma tabela
    if (!$usuario) {
        $_SESSION['resposta'] = "Usuário não validado!";
        header("Location: " . BASE_URL . "adm/eventos");
        exit;
    }

} catch (Exception $erro) {
    $_SESSION['resposta'] = "Erro inesperado. Tente novamente.";
    header("Location: " . BASE_URL . "adm/eventos");
    exit;
}
?>
<main>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="flex gap-2 text-2xl font-bold items-center mb-2 text-texto-preto">
                Meu Perfil
            </h2>
            <span class="font-normal text-xl text-texto-opaco" id="data_atual"></span>
        </div>
        <div class="flex gap-3">
            <?php if($cargo == 1):?>
            <button class="btn-novo" onclick="abrirEditarModal('perfil', <?= htmlspecialchars($usuario['id']) ?>)">
                <i class="bi bi-pencil-square"></i> Editar Perfil
            </button>
            <?php endif; ?>
            <a href="../sair" class="rounded-lg p-2 text-white bg-red-500 cursor-pointer hover:opacity-80"><i
                    class="bi bi-door-closed"></i> Sair
                da
                conta</a>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-5 mt-5">
        <div class="card-perfil h-full row-span-2 gap-4">
            <div class="w-full flex justify-center items-center flex-col gap-2">
                <span class="foto"><?= htmlspecialchars(substr($nome, 0, 1)) ?></span>
                <h3 class="nome"><?= htmlspecialchars($nome) ?></h3>
                <span
                    class="<?= ($cargo == 1) ? "bg-green-200 text-green-600" : "bg-blue-200 text-blue-600" ?> rounded-full px-2"><?= ($cargo == 1) ? "Voluntário" : "Administrador" ?></span>
            </div>
            <ul>
                <li><i class="bi bi-envelope"></i><?= htmlspecialchars($usuario['email']) ?></li>
                <?php if(isset($usuario["telefone"])):?>
                <li><i class="bi bi-telephone"></i><?= formatarTelefone(htmlspecialchars($usuario['telefone'])) ?></li>
                <?php endif;?>
            </ul>
        </div>
        <div class="card-perfil row-span-2 gap-10">
            <h3>Meu Impacto</h3>
            <?php 
                $sql = "SELECT COUNT(id_voluntario) AS eventos_inscritos FROM presencas WHERE id_voluntario = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("i",$usuario["id"]);
                $stmt->execute();
                $stmt->bind_result($eventos_inscritos);
                $stmt->fetch();
                $stmt->close();
            ?>
            <div class="w-full flex justify-between items-center">
                <div class="flex items-start w-full flex-col">
                    <p class="text-base text-texto-opaco">Eventos inscritos</p>
                    <span class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars($eventos_inscritos) ?></span>
                </div>
                <span
                    class="bg-blue-100 rounded-lg w-15 h-13 text-blue-600 flex justify-center items-center text-2xl"><i
                        class="bi bi-calendar"></i></span>
            </div>
            <?php 
                $sql = "SELECT COUNT(id_voluntario) AS eventos_participados FROM presencas WHERE id_voluntario = ? AND status = 1";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("i",$usuario["id"]);
                $stmt->execute();
                $stmt->bind_result($eventos_participados);
                $stmt->fetch();
                $stmt->close();
            ?>
            <div class="w-full flex justify-between items-center">
                <div class="flex items-start w-full flex-col">
                    <p class="text-base text-texto-opaco">Eventos participados</p>
                    <span
                        class="text-2xl font-bold text-texto-preto"><?= htmlspecialchars($eventos_participados) ?></span>
                </div>
                <span
                    class="bg-green-100 rounded-lg w-15 h-13 text-green-600 flex justify-center items-center text-2xl"><i
                        class="bi bi-calendar-check"></i></span>
            </div>
        </div>
        <div class="card-perfil h-35">
            <h3>Habilidades</h3>
            <?php if(isset($usuario["habilidades"])):?>
            <div class="flex justify-start items-center gap-2 flex-wrap">
                <?php
                    $habilidades = explode(",", $usuario['habilidades']);
                    for ($i = 0; $i < count($habilidades); $i++):
                ?>
                <span
                    class="bg-blue-100 rounded-xl px-3 text-blue-600 text-base"><?= htmlspecialchars($habilidades[$i]) ?></span>
                <?php endfor; ?>
            </div>
            <?php else: ?>
            <p>Nenhuma Habilidade Cadastrada</p>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php $tipo_modal = "voluntarios"; ?>
<?php require_once __DIR__ . "/../includes/modal.php"; ?>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>