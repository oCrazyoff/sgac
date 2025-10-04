<?php
$n_valida = true;
$titulo = "Eventos";
require_once __DIR__ . "/../includes/inicio.php";
?>
<header class="shadow-sm">
    <div class="interface flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="icon-degrade rounded-lg">
                <i class="bi bi-calendar2-heart text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">SGAC</h1>
                <p class="text-sm text-gray-500">Sistema de Gestão de Eventos</p>
            </div>
        </div>
        <nav>
            <a href="login" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                <i class="bi bi-person-gear mr-3 text-blue-600 text-xl"></i>
                Administrativo
            </a>
        </nav>
    </div>
</header>
<?php require_once __DIR__ . "/../includes/fim.php"; ?>
