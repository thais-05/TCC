<?php
// setar o fuso horario
date_default_timezone_set('America/Sao_Paulo');

// Criando a conexão
$conn = new mysqli('localhost', 'root', '', 'una');

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>