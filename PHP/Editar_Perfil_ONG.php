<?php
require 'Conexao.php';
session_start();

if (!isset($_SESSION['id_ong'])) {
    header("Location: Login.php");
    exit();
}

$id_ong = $_SESSION['id_ong'];

try {
    // Consulta os dados do perfil para preencher o formulário
    $query = "SELECT nome, email_ong, cnpj, telefone, cep, endereco, endereco_numero, descricao FROM ongs WHERE id_ong = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_ong);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("ONG não encontrada.");
    }

    $user = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recebe os dados do formulário
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $cnpj = trim($_POST['cnpj']);
        $telefone = trim($_POST['telefone']);
        $cep = trim($_POST['cep']);
        $endereco = trim($_POST['endereco']);
        $endereco_numero = trim($_POST['endereco_numero']);
        $descricao = trim($_POST['descricao']);

        // Validações
        if (!$nome || !$email || !$telefone || !$cnpj || !$cep || !$endereco || !$endereco_numero || !$descricao) {
            throw new Exception("Todos os campos devem ser preenchidos.");
        }

        // Atualiza os dados no banco de dados
        $update_query = "UPDATE ongs SET nome = ?, email_ong = ?, cnpj = ?, telefone = ?, cep = ?, endereco = ?, endereco_numero = ?, descricao = ? WHERE id_ong = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssssssssi", $nome, $email, $cnpj, $telefone, $cep, $endereco, $endereco_numero, $descricao, $id_ong);

        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "Perfil atualizado com sucesso!";
            header("Location: Perfil_ONG.php");
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
?>

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
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
        <?php unset($_SESSION['error_message']);
        endif; ?>
        <form method="POST" action="Editar_Perfil_ong.php">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email_ong']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="cnpj" class="form-label">CNPJ</label>
                <input type="text" id="cnpj" name="cnpj" class="form-control" value="<?= htmlspecialchars($user['cnpj']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control" value="<?= htmlspecialchars($user['telefone']) ?>" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" id="cep" name="cep" class="form-control" value="<?= htmlspecialchars($user['cep']) ?>" required>
                </div>
                <div class="col-md-5">
                    <label for="endereco" class="form-label">Endereço</label>
                    <input type="text" id="endereco" name="endereco" class="form-control" value="<?= htmlspecialchars($user['endereco']) ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="endereco_numero" class="form-label">Número</label>
                    <input type="text" id="endereco_numero" name="endereco_numero" class="form-control" value="<?= htmlspecialchars($user['endereco_numero']) ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" required><?= htmlspecialchars($user['descricao']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </main>
</body>

</html>