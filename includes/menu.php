<aside class="menu-layer">
    <div class="p-6 border-b border-gray-200 ">
        <div class="flex items-center space-x-3">
            <div class="icon-degrade rounded-lg">
                <i class="bi bi-calendar2-heart text-white text-lg"></i>
            </div>
            <h1 class="text-lg font-bold text-gray-900">
                SGAC
            </h1>
        </div>
    </div>
    <nav class="mt-8">
        <?php if ($_SESSION['cargo'] == 0): ?>
        <!--adm-->
        <a href="<?= BASE_URL . "adm/voluntarios" ?>"
            class="menu-itens <?= ($rota == 'adm/voluntarios') ? 'active' : '' ?>">
            <i class="bi bi-people"></i>
            <span>Voluntários</span>
        </a>
        <a href="<?= BASE_URL . "adm/eventos" ?>" class="menu-itens <?= ($rota == 'adm/eventos') ? 'active' : '' ?>">
            <i class="bi bi-calendar4"></i>
            <span>Eventos</span>
        </a>
        <a href="<?= BASE_URL . "adm/presenca" ?>" class="menu-itens <?= ($rota == 'adm/presenca') ? 'active' : '' ?>">
            <i class="bi bi-person-check"></i>
            <span>Presença</span>
        </a>
        <a href="<?= BASE_URL . "adm/recursos" ?>" class="menu-itens <?= ($rota == 'adm/recursos') ? 'active' : '' ?>">
            <i class="bi bi-gift"></i>
            <span>Recursos</span>
        </a>
        <a href="<?= BASE_URL . "adm/relatorios" ?>"
            class="menu-itens <?= ($rota == 'adm/relatorios') ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i>
            <span>Relatórios</span>
        </a>
        <?php else: ?>
        <!--voluntario-->
        <a href="<?= BASE_URL . "eventos" ?>" class="menu-itens <?= ($rota == 'eventos') ? 'active' : '' ?>">
            <i class="bi bi-calendar4"></i>
            <span>Eventos</span>
        </a>
        <a href="<?= BASE_URL . "recursos" ?>" class="menu-itens <?= ($rota == 'recursos') ? 'active' : '' ?>">
            <i class="bi bi-gift"></i>
            <span>Recursos</span>
        </a>
        <?php endif; ?>
    </nav>
    <a href="<?= BASE_URL . "adm/perfil"?>">
        <div class="absolute bottom-6 left-6 right-6 space-y-4">
            <div class="flex items-center justify-start space-x-3 bg-gray-50 rounded-lg">
                <div class="icon-degrade">
                    <i class="bi bi-person text-white text-xl"></i>
                </div>
                <div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">
                            <?= htmlspecialchars(explode(' ', trim($_SESSION['nome']))[0]) ?>
                        </p>
                        <p class="text-xs text-gray-500">
                            <?= ($_SESSION['cargo'] == 0) ? 'Administrador' : 'Voluntário' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </a>
</aside>