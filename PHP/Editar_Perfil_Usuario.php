<!-- Primeira Parte
<?php
require 'Conexao.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['id_usuario'];

try {
    // Consulta os dados do usuário para pré-preenchimento do formulário
    $query = "SELECT nome, email, telefone FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Usuário não encontrado.");
    }

    $user = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recebe os dados do formulário
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $telefone = trim($_POST['telefone']);

        // Validações
        if (!$nome || !$email || !$telefone) {
            throw new Exception("Todos os campos devem ser preenchidos.");
        }

        // Atualiza os dados no banco
        $update_query = "UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id_usuario = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssi", $nome, $email, $telefone, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "Perfil atualizado com sucesso!";
            header("Location: Perfil_Usuario.php");
            exit();
        } else {
            throw new Exception("Erro ao atualizar perfil: " . $update_stmt->error);
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error_message'] = "Erro ao carregar ou atualizar perfil.";
    header("Location: Erro_Cadastro.php");
    exit();
}
?>   -->

<!doctype html>
<html lang="pt-br">

<head>
    <title>Editar Perfil</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/Editar_Perfil.css" type="text/css">
</head>

<body>
    <main class="container mt-5">
        <h1>Editar Perfil</h1>
        <form method="POST" action="Editar_Perfil_Usuario.php">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" value="<?= htmlspecialchars($user['telefone']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </main>
</body>

</html>
