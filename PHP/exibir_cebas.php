<?php
require_once 'Conexao.php';

$id_ong = $_GET['id_ong'] ?? null;
if (!$id_ong) {
    die('Erro: ONG não especificada.');
}

$query = 'SELECT cebas FROM ongs WHERE id_ong = ?';
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_ong);
$stmt->execute();
$stmt->bind_result($cebas);
$stmt->fetch();
$stmt->close();

if (!$cebas) {
    die('Erro: CEBAS não encontrado.');
}

header('Content-Type: image/jpeg');
// Ajuste conforme necessário (image/png, image/webp, etc.)
echo $cebas;
?>