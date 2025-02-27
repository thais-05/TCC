<?php
header("Refresh: 5; url=Login.php"); // Redireciona para a página de Login após 5 segundos
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro no Cadastro</title>
    <link rel="stylesheet" href="../CSS/Sucesso_Erro.css"> <!-- Link para o CSS externo -->
</head>

<body>
    <div class="mensagem erro">
        <h1>Ocorreu um erro no seu login!</h1>
        <p>Redirecionando para a tela de cadastro em 5 segundos...</p>
    </div>
</body>

</html>