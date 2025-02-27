<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'Conexao.php'; // Inclui a conexão com o banco de dados
ini_set('display_errors', 1);
error_reporting(E_ALL);

mysqli_set_charset($conn, "utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Captura e sanitização dos dados
        $nome = trim(mysqli_real_escape_string($conn, $_POST['nome'] ?? ''));
        $email = trim(strtolower(mysqli_real_escape_string($conn, $_POST['email_ong'] ?? '')));
        $senha = $_POST['senha'] ?? '';
        $confirma_senha = $_POST['confirma_senha'] ?? '';
        $cnpj = trim(mysqli_real_escape_string($conn, $_POST['cnpj'] ?? ''));
        $telefone = trim(mysqli_real_escape_string($conn, $_POST['telefone'] ?? ''));
        $cep = trim(mysqli_real_escape_string($conn, $_POST['cep'] ?? null));
        $endereco = trim(mysqli_real_escape_string($conn, $_POST['endereco'] ?? null));
        $endereco_numero = trim(mysqli_real_escape_string($conn, $_POST['endereco_numero'] ?? null));
        $descricao = trim(mysqli_real_escape_string($conn, $_POST['descricao'] ?? null));
        $rede_social = trim(mysqli_real_escape_string($conn, $_POST['rede_social'] ?? null));
        $link = trim(mysqli_real_escape_string($conn, $_POST['link'] ?? null));
        $cebas = $_FILES['cebas'] ?? null;
        $tipo = 'ong';

        // Validação básica
        if (empty($nome) || empty($email) || empty($senha) || empty($cnpj) || empty($telefone)) {
            throw new Exception("Todos os campos obrigatórios devem ser preenchidos.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Formato de email inválido.");
        }

        if ($senha !== $confirma_senha) {
            throw new Exception("As senhas não coincidem.");
        }

        if (strlen($senha) < 8 || strlen($senha) > 16) {
            throw new Exception("A senha deve ter entre 8 e 16 caracteres.");
        }

        if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
            throw new Exception("URL do link adicional inválida. Certifique-se de incluir 'http://' ou 'https://'.");
        }

        if ($rede_social && !filter_var($rede_social, FILTER_VALIDATE_URL)) {
            throw new Exception("URL da rede social inválida.");
        }

        // Upload do CEBAS (opcional)
        $cebasName = null;
        if ($cebas && $cebas['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/cebas/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $cebasName = uniqid() . '-' . basename($cebas['name']);
            $uploadPath = $uploadDir . $cebasName;

            if (!move_uploaded_file($cebas['tmp_name'], $uploadPath)) {
                throw new Exception("Erro ao fazer upload do CEBAS.");
            }
        }

        // Verifica duplicidade de email ou CNPJ
        $sqlCheck = "SELECT COUNT(*) FROM ongs WHERE email_ong = ? OR cnpj = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ss", $email, $cnpj);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            throw new Exception("Email ou CNPJ já cadastrados.");
        }

        // Hash da senha
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        // Insere os dados no banco
        $sql = "INSERT INTO ongs (nome, email_ong, senha, cnpj, telefone, cep, endereco, endereco_numero, descricao, cebas, tipo, rede_social, link, criado_em)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssssssssss",
            $nome,
            $email,
            $senha_hash,
            $cnpj,
            $telefone,
            $cep,
            $endereco,
            $endereco_numero,
            $descricao,
            $cebasName,
            $tipo,
            $rede_social,
            $link
        );

        if (!$stmt->execute()) {
            throw new Exception("Erro ao registrar a ONG: " . $stmt->error);
        }

        // Redireciona para a página de sucesso
        header("Location: Sucesso_Cadastro.php");
        exit;
    } catch (Exception $e) {
        // Mostra o erro e registra no log
        error_log("Erro: " . $e->getMessage());
        echo "<p>Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
