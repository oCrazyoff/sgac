<?php
$n_valida = true;
$titulo = "Login";
require_once "includes/inicio.php";
?>
    <main>
        <form action="fazer_login" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Login</button>
        </form>
    </main>
<?php require_once "includes/fim.php"; ?>