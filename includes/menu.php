<?php
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>

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
        <a href="" class="<?= ($currentPage == 'voluntarios') ? 'menu-itens' : 'menu-itens-active' ?>">
            <i class="bi bi-people text-xl"></i>
            <span>Voluntários</span>
        </a>
        <a href="" class="<?= ($currentPage == 'eventos') ? 'menu-itens-active' : 'menu-itens' ?>">
            <i class="bi bi-calendar4 text-lg"></i>
            <span>Eventos</span>
        </a>
        <a href="" class="<?= ($currentPage == 'presenca') ? 'menu-itens-active' : 'menu-itens' ?>">
            <i class="bi bi-person-check text-xl"></i>
            <span>Presença</span>
        </a>
        <a href="" class="<?= ($currentPage == 'recursos') ? 'menu-itens-active' : 'menu-itens' ?>">
            <i class="bi bi-gift text-lg"></i>
            <span>Recursos</span>
        </a>
        <a href="" class="<?= ($currentPage == 'relatorios') ? 'menu-itens-active' : 'menu-itens' ?>">
            <i class="bi bi-graph-up text-lg"></i>
            <span>Relatórios</span>
        </a>
    </nav>
    <div class="absolute bottom-6 left-6 right-6 space-y-4">
        <div class="bg-gray-50 rounded-lg p-3">
            <div class="flex items-center space-x-3 mb-3">
                <div class="icon-degrade">
                    <i class="bi bi-person text-white text-xl"></i>
                </div>
                <div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">
                            Usuario
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