<?php
session_start();
require 'Conexao.php'; // Inclui a conexão com o banco de dados

// Verifica se a sessão está ativa e se os valores necessários existem
if (!isset($_SESSION['tipo'], $_SESSION['id_usuario']) && !isset($_SESSION['id_ong'])) {
    header('Location: Login.php');
    exit;
}

$tipo = $_SESSION['tipo'];
$id_usuario = $_SESSION['id_usuario'] ?? null;
$id_ong = $_SESSION['id_ong'] ?? null;

// Redirecionar com base no tipo do usuário
switch ($tipo) {
    case 'usuario':
        if ($id_usuario) {
            header("Location: Perfil_Usuario.php?id_usuario=$id_usuario");
        } else {
            header('Location: Erro.php');
        }
        break;

    case 'ong':
        if ($id_ong) {
            header("Location: Perfil_ONG.php?id_ong=$id_ong");
        } else {
            header('Location: Erro.php');
        }
        break;

    default:
        header('Location: Erro.php');
        break;
}
exit;
?>
