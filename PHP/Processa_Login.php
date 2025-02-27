<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'Conexao.php';
mysqli_set_charset($conn, "utf8mb4");

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método de requisição inválido.");
    }

    $tipo = $_POST['tipo'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;
    $cnpj = $_POST['cnpj'] ?? null;

    if (empty($tipo) || empty($email) || empty($senha)) {
        throw new Exception("Preencha todos os campos.");
    }

    if (!in_array($tipo, ['usuario', 'ong'])) {
        throw new Exception("Tipo de login inválido.");
    }

    $email = mysqli_real_escape_string($conn, $email);

    if ($tipo === 'ong' && empty($cnpj)) {
        throw new Exception("CNPJ é obrigatório para ONGs.");
    }

    $tabela = ($tipo === 'usuario') ? 'usuarios' : 'ongs';
    $campo_id = ($tipo === 'usuario') ? 'id_usuario' : 'id_ong';
    $campo_email = ($tipo === 'usuario') ? 'email' : 'email_ong';
    $campo_senha = 'senha';

    // Ajusta a consulta para não buscar o CNPJ caso seja um usuário
    $sql = "SELECT $campo_id, $campo_senha" . ($tipo === 'ong' ? ", cnpj" : "") . " FROM $tabela WHERE $campo_email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Erro ao preparar consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if (!$resultado || $resultado->num_rows === 0) {
        throw new Exception("Credenciais inválidas.");
    }

    $dados = $resultado->fetch_assoc();

    // Apenas verifica o CNPJ caso seja uma ONG
    if ($tipo === 'ong' && isset($dados['cnpj']) && $dados['cnpj'] !== $cnpj) {
        throw new Exception("CNPJ inválido.");
    }

    if (!password_verify($senha, $dados[$campo_senha])) {
        throw new Exception("Credenciais inválidas.");
    }

    $_SESSION[$campo_id] = $dados[$campo_id];
    $_SESSION['tipo'] = $tipo;

    header("Location: Tela_Inicial.php");
    exit;
} catch (Exception $e) {
    error_log("Erro no login: " . $e->getMessage());
    header("Location: Erro_Login.php?erro=" . urlencode($e->getMessage()));
    exit;
}
