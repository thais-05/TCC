<?php
session_start();

if (!isset($_SESSION['id_ong'])) {
    echo '<script>alert("Erro: usuário não autenticado. Por favor, faça login.");</script>';
    echo '<script>window.location.href = "Login.php";</script>';
    exit;
}

require_once 'Conexao.php';

$id_ong = $_SESSION['id_ong'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Excluir a ONG e suas dependências do banco de dados
        $query = "DELETE FROM ongs WHERE id_ong = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $id_ong);
        if (!$stmt->execute()) {
            throw new Exception("Erro ao excluir o perfil: " . $stmt->error);
        }

        $stmt->close();

        // Encerrar a sessão
        session_destroy();

        echo '<script>alert("Perfil excluído com sucesso.");</script>';
        echo '<script>window.location.href = "Login.php";</script>';
        exit;
    }
} catch (Exception $e) {
    echo '<script>alert("Erro: ' . htmlspecialchars($e->getMessage()) . '");</script>';
    echo '<script>window.history.back();</script>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Excluir Perfil</h1>
        <p class="text-center">Tem certeza de que deseja excluir seu perfil? Esta ação não pode ser desfeita.</p>
        <form method="POST" class="text-center">
            <button type="submit" class="btn btn-danger">Excluir Perfil</button>
            <a href="Perfil_ONG.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>