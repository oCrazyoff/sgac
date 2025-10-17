<?php
$n_valida = true;
$titulo = "Eventos";
require_once __DIR__ . "/../includes/inicio.php";

// puxando os 3 ultimos eventos
$sql = "SELECT * FROM eventos ORDER BY created_at DESC LIMIT 3";
$stmt = $conexao->prepare($sql);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();

// placeholder img
$link_placeholder = 'https://blog.iprocess.com.br/wp-content/uploads/2021/11/placeholder.png';
?>
<header class="shadow-sm border-b border-gray-200">
    <div class="interface flex items-center justify-between px-[10%] py-2">
        <div class="flex items-center space-x-3">
            <div class="icon-degrade rounded-lg w-10 h-10">
                <i class="bi bi-calendar2-heart text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">SGAC</h1>
                <p class="text-sm text-gray-500">Sistema de Gestão de Eventos</p>
            </div>
        </div>
        <nav>
            <a href="login" class="font-semibold flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                <i class="bi bi-people mr-2 text-blue-600 text-xl"></i>
                Login
            </a>
        </nav>
    </div>
</header>
<main class="p-0">
    <!-- Hero -->
    <section class="bg-blue-50 w-full py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto flex flex-col items-center justify-center gap-4">
            <h2 class="text-6xl font-bold text-center text-gray-900">
                Transforme Vidas Através do <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 block">
                    Voluntariado
                </span>
            </h2>
            <p class="text-gray-700 text-xl text-center max-w-3xl px-4">
                Junte-se a milhares de voluntários que estão fazendo a diferença em nossa comunidade. Cada pequena
                ação pode gerar um grande impacto social.
            </p>
            <div class="flex gap-6 mt-6">
                <a href="cadastro"
                    class="cursor-pointer flex items-center justify-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="bi bi-calendar4 text-lg"></i>
                    Ver Eventos
                    <i class="bi bi-arrow-right text-lg"></i>
                </a>
                <a href="cadastro"
                    class="cursor-pointer flex items-center justify-center gap-2 bg-white text-blue-600 px-8 py-3 rounded-xl font-semibold hover:bg-gray-50 border-2 border-blue-600 transform hover:scale-105 transition-all duration-200">
                    <i class="bi bi-heart text-lg"></i>
                    Seja Voluntário
                </a>
            </div>
        </div>
    </section>

    <!-- Impactos -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white ">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Nosso Impacto
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i
                        class="bi bi-people h-12 w-12 bg-blue-500 p-3 rounded-xl text-white items-center justify-center flex text-2xl"></i>
                    <p class="text-3xl font-bold text-gray-900 mt-4">
                        1,247
                    </p>
                    <p class="text-gray-600 text-sm mt-1">
                        Voluntários Ativos
                    </p>
                </div>
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i
                        class="bi bi-calendar4 h-12 w-12 bg-green-500 p-3 rounded-xl text-white items-center justify-center flex text-2xl"></i>
                    <p class="text-3xl font-bold text-gray-900 mt-4">
                        156
                    </p>
                    <p class="text-gray-600 text-sm mt-1">
                        Eventos Realizados
                    </p>
                </div>
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i
                        class="bg-red-500 h-12 w-12 rounded-xl text-white items-center justify-center flex bi bi-heart text-2xl"></i>

                    <p class="text-3xl font-bold text-gray-900 mt-4">
                        8,432
                    </p>
                    <p class="text-gray-600 text-sm mt-1">
                        Horas de voluntariado
                    </p>
                </div>
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i
                        class="bi bi-bullseye h-12 w-12 bg-purple-500 p-3 rounded-xl text-white items-center justify-center flex text-2xl"></i>
                    <p class="text-3xl font-bold text-gray-900 mt-4">
                        15,678
                    </p>
                    <p class="text-gray-600 text-sm mt-1">
                        Pessoas Impactadas
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Proximos Eventos -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-blue-50">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-12">
                <h3 class="text-3xl font-bold text-gray-900">
                    Próximos Eventos
                </h3>
                <a href="cadastro"
                    class="text-blue-600 hover:text-blue-700 font-semibold flex items-center transition-colors duration-200 gap-2">
                    <span>Ver todos</span>
                    <i class="bi bi-arrow-right text-lg"></i>
                </a>
            </div>

            <!--container cards eventos-->
            <div class="grid grid-cols-3 gap-5 mt-5">
                <?php if ($resultado->num_rows === 0) : ?>
                <div class="flex col-span-4 flex-col justify-center items-center gap-4 py-10">
                    <i class="bi bi-archive text-5xl text-gray-500"></i>
                    <p class="text-2xl text-center text-gray-500">Nenhum evento disponivel no momento.</p>
                </div>
                <?php else : ?>
                <?php while ($row = $resultado->fetch_assoc()) : ?>
                <!--card eventos-->
                <div
                    class="card-eventos hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 cursor-pointer group/evento">
                    <div class="img-container overflow-hidden">
                        <img class="object-cover group-hover/evento:scale-110 transition-transform duration-500"
                            src="<?= htmlspecialchars($row['link_img'] ?: $link_placeholder) ?>"
                            alt="Imagem do evento <?= htmlspecialchars($row['nome']) ?>"
                            onerror="this.onerror=null; this.src='<?= $link_placeholder ?>';">
                        <?php if ($row['status'] == 0): ?>
                        <span class="tag-status bg-blue-100 text-blue-600">
                            Publicado
                        </span>
                        <?php elseif ($row['status'] == 1): ?>
                        <span class="tag-status bg-green-100 text-green-600">
                            Concluido
                        </span>
                        <?php elseif ($row['status'] == 2): ?>
                        <span class="tag-status bg-red-100 text-red-600">
                            Cancelado
                        </span>
                        <?php endif; ?>
                        <span class="tag-tipo">
                            <?= htmlspecialchars($row['tipo']) ?>
                        </span>
                    </div>
                    <div class="txt-card">
                        <h2><?= htmlspecialchars($row['nome']) ?></h2>
                        <p class="descricao">
                            <?= htmlspecialchars($row['descricao']) ?>
                        </p>
                        <p>
                            <i class="bi bi-calendar"></i>
                            <span id="data-hora">
                                <?= htmlspecialchars($row['data_hora']) ?>
                            </span>
                        </p>
                        <p>
                            <i class="bi bi-geo-alt"></i>
                            <?= htmlspecialchars($row['endereco']) ?>
                        </p>
                        <p>
                            <i class="bi bi-people"></i>
                            <span>
                                <?php
                                        // puxando quantos voluntarios tem nesse eventos
                                        $sql = "SELECT COUNT(*) FROM presencas WHERE id_evento = ?";
                                        $stmt = $conexao->prepare($sql);
                                        $stmt->bind_param("i", $row['id']);
                                        $stmt->execute();
                                        $stmt->bind_result($presencas);
                                        $stmt->fetch();
                                        $stmt->close();
                                        ?>
                                <?= htmlspecialchars($presencas) ?>/<?= htmlspecialchars($row['meta_voluntarios']) ?>
                                Voluntários
                            </span>
                        </p>
                        <?php
                                $meta = (int)$row['meta_voluntarios'];
                                $inscritos = (int)$presencas;
                                $percentual = $meta > 0 ? round(($inscritos / $meta) * 100) : 0;
                                ?>
                        <div class="mt-5">
                            <p class="flex w-full items-center justify-between">
                                Inscrições
                                <span><?= $percentual ?>%</span>
                            </p>
                            <div class="container-barra">
                                <div class="barra" style="width: <?= $percentual ?>%;"></div>
                            </div>
                        </div>
                        <p class="mt-5">
                            <span class="text-black font-bold">Objetivos: </span>
                            <?= htmlspecialchars($row['objetivos']) ?>
                        </p>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="w-full py-4 px-4 sm:px-6 lg:px-8 bg-black/85">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <p class="font-semibold text-gray-500 text-sm">&copy;2025 SGAC. Feito para o Entec. Todos os direitos
                reservados.</p>
            <div class="flex items-center space-x-4">
                <a href="#" class="text-gray-500 hover:text-blue-600 transition-colors">
                    <i class="bi bi-facebook text-2xl"></i>
                </a>
                <a href="#" class="text-gray-500 hover:text-blue-600 transition-colors">
                    <i class="bi bi-twitter text-2xl"></i>
                </a>
                <a href="#" class="text-gray-500 hover:text-blue-600 transition-colors">
                    <i class="bi bi-instagram text-2xl"></i>
                </a>
            </div>
        </div>
    </footer>
</main>
<script>
// formatando data e hora
document.querySelectorAll('[id="data-hora"]').forEach(span => {
    const texto = span.textContent.trim();
    const data = new Date(texto.replace(" ", "T"));

    if (isNaN(data)) return; // evita erro se a data estiver inválida

    const formatador = new Intl.DateTimeFormat("pt-BR", {
        day: "numeric",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit"
    });

    const partes = formatador.formatToParts(data);
    const resultado = `${partes.find(p => p.type === 'day').value} de ` +
        `${partes.find(p => p.type === 'month').value} de ` +
        `${partes.find(p => p.type === 'year').value} às ` +
        `${partes.find(p => p.type === 'hour').value}:${partes.find(p => p.type === 'minute').value}`;

    span.textContent = resultado;
});
</script>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>