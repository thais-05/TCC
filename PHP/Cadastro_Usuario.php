<?php
session_start();
require 'Conexao.php'; // Inclui a conexão com o banco de dados
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $senha = $_POST['senha'] ?? '';
        $confirma_senha = $_POST['confirma_senha'] ?? '';
        $telefone = trim($_POST['telefone'] ?? '');

        if (empty($nome) || empty($email) || empty($senha) || empty($telefone)) {
            throw new Exception("Todos os campos obrigatórios devem ser preenchidos.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Formato de email inválido.");
        }

        if ($senha !== $confirma_senha) {
            throw new Exception("As senhas não coincidem.");
        }

        if (strlen($senha) < 8) {
            throw new Exception("A senha deve ter no mínimo 8 caracteres.");
        }

        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        $sqlCheck = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            throw new Exception("Email já cadastrado.");
        }

        $sql = "INSERT INTO usuarios (nome, email, senha, telefone, tipo, criado_em) VALUES (?, ?, ?, ?, 'usuario', NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome, $email, $senha_hash, $telefone);

        if ($stmt->execute()) {
            header("Location: Sucesso_Cadastro.php");
            exit;
        } else {
            throw new Exception("Erro ao registrar usuário: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "<p>Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
