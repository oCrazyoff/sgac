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
        <!-- Abaixo é um exemplo depois tem que inverter a ordem -->
        <a href="voluntarios" class="menu-itens <?= ($rota == 'adm/voluntarios') ? 'active' : '' ?>">
            <i class="bi bi-people"></i>
            <span>Voluntários</span>
        </a>
        <a href="eventos" class="menu-itens <?= ($rota == 'adm/eventos') ? 'active' : '' ?>">
            <i class="bi bi-calendar4"></i>
            <span>Eventos</span>
        </a>
        <a href="presenca" class="menu-itens <?= ($rota == 'adm/presenca') ? 'active' : '' ?>">
            <i class="bi bi-person-check"></i>
            <span>Presença</span>
        </a>
        <a href="doacoes" class="menu-itens <?= ($rota == 'adm/doacoes') ? 'active' : '' ?>">
            <i class="bi bi-gift"></i>
            <span>Doações</span>
        </a>
        <a href="relatorios" class="menu-itens <?= ($rota == 'adm/relatorios') ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i>
            <span>Relatórios</span>
        </a>
    </nav>
    <div class="absolute bottom-6 left-6 right-6 space-y-4">
        <div class="bg-gray-50 rounded-lg p-3">
            <div class="flex items-center justify-center space-x-3">
                <div class="icon-degrade">
                    <i class="bi bi-person text-white text-xl"></i>
                </div>
                <div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">
                            <?= htmlspecialchars($_SESSION['nome']) ?>
                        </p>
                        <p class="text-xs text-gray-500">
                            Administrador
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>