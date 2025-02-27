<?php
session_start();

// Verifica se o login foi bem-sucedido e os dados estão na sessão
if (!isset($_SESSION['usuario']) || !isset($_SESSION['tipo'])) {
    header("Location: Login.php");
    exit;
}

header("Refresh: 5; url=Tela_Inicial.php"); // Redireciona para a página de tela inicial após 5 segundos
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Realizado</title>
    <link rel="stylesheet" href="../CSS/Sucesso_Erro.css"> <!-- Link para o CSS externo -->
</head>

<body>
    <div class="mensagem sucesso">
        <h1>Login realizado com sucesso!</h1>
        <p>Bem-vindo(a), <?= htmlspecialchars($_SESSION['usuario']); ?>!</p>
        <p>Redirecionando para a tela inicial em 5 segundos...</p>
    </div>
</body>

</html>