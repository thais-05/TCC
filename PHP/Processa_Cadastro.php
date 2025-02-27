<?php
session_start();

// Verificar se o campo 'tipo' existe e não está vazio
if (!isset($_POST['tipo']) || empty($_POST['tipo'])) {
    die("<script>showPopup('Tipo de cadastro inválido!');</script>");
}

$tipo = $_POST['tipo'];

// Redirecionar para o arquivo correto com base no tipo
switch ($tipo) {
    case 'usuario':
        require 'Cadastro_Usuario.php';
        break;
    case 'ong':
        require 'Cadastro_ONG.php';
        break;
    case 'empresa':
        require 'Cadastro_Empresa.php';
        break;
    default:
        die("<script>showPopup('Tipo de cadastro não reconhecido!');</script>");
}
