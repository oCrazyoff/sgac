<?php
$n_valida = true;
$titulo = "Cadastro";
require_once "includes/inicio.php";
?>
<main class="items-center justify-center flex bg-blue-50">
    <form action="fazer_cadastro" method="POST"
        class="flex w-full flex-col items-center justify-center space-y-6 rounded-xl bg-white px-[4%] py-6 shadow-xl/30 lg:w-auto lg:min-w-lg lg:px-6 lg:py-6">
        <!-- csrf -->
        <input type="hidden" name="csrf" id="csrf" value="<?= gerarCSRF() ?>">
        <div class="flex flex-col items-center justify-center gap-2">
            <div class="icon-degrade rounded-lg w-15 h-15">
                <i class="bi bi-calendar2-heart text-white text-4xl"></i>
            </div>
            <h1 class="text-blue-600 text-center font-semibold text-4xl">
                SGAC
            </h1>
        </div>
        <div class="w-full">
            <label for="nome" class="text-blue-600 font-semibold">Nome</label>
            <div class="flex flex-col w-full relative">
                <i class="bi bi-person absolute top-1/2 left-3 -translate-y-1/2 transform text-xl text-gray-500"></i>
                <input type="nome" name="nome" placeholder="Nome completo"
                    class="w-full rounded-lg border border-gray-300 bg-gray-200 py-3 pr-4 pl-10 outline-none focus:border-none focus:ring-2 focus:ring-blue-600"
                    required>
            </div>
        </div>
        <div class="w-full">
            <label for="email" class="text-blue-600 font-semibold">Email</label>
            <div class="flex flex-col w-full relative">
                <i class="bi bi-envelope absolute top-1/2 left-3 -translate-y-1/2 transform text-xl text-gray-500"></i>
                <input type="email" name="email" placeholder="Email"
                    class="w-full rounded-lg border border-gray-300 bg-gray-200 py-3 pr-4 pl-10 outline-none focus:border-none focus:ring-2 focus:ring-blue-600"
                    required>
            </div>
        </div>
        <div class="w-full">
            <label for="password" class="text-blue-600 font-semibold">Senha</label>
            <div class="flex flex-col w-full relative">
                <i class="bi bi-lock absolute top-1/2 left-3 -translate-y-1/2 transform text-xl text-gray-500"></i>
                <input type="password" name="senha" placeholder="Senha"
                    class="w-full rounded-lg border border-gray-300 bg-gray-200 py-3 pr-4 pl-10 outline-none focus:border-none focus:ring-2 focus:ring-blue-600"
                    required>
            </div>
        </div>
        <button type="submit"
            class="w-full cursor-pointer rounded-lg bg-blue-600 py-3 font-medium text-white transition-colors hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">Login</button>
    </form>
</main>
<?php require_once "includes/fim.php"; ?>