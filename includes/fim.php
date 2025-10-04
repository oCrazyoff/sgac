</body>
<?php include("div_erro.php"); ?>

<script>
    const hoje = new Date();

    // Opções de formatação
    const opcoes = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};

    // Formatar para português do Brasil
    const dataFormatada = hoje.toLocaleDateString('pt-BR', opcoes);

    // Colocar no HTML
    document.getElementById('data_atual').textContent = dataFormatada;
</script>
</html>