<!-- Primeira Parte
<?php
require 'Conexao.php';
session_start();

if (!isset($_SESSION['id_ong'])) {
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['id_ong'];

$id_publi = $_GET['id'];

echo $id_publi;

try {
    // Consulta os dados do usuário para pré-preenchimento do formulário
    $query = "SELECT nome, legenda, imagem FROM publicacoes WHERE id_publi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_publi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Usuário não encontrado.");
    }

    $user = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recebe os dados do formulário
        $nome = trim($_POST['nome']);
        $legenda = trim($_POST['legenda']);
        $imagem = trim($user['imagem']);

        // Validações
        if (!$nome || !$legenda || !$imagem) {
            throw new Exception("Todos os campos devem ser preenchidos.");
        }

        // Atualiza os dados no banco
        $update_query = "UPDATE publicacoes SET nome = ?, legenda = ?, imagem = ? WHERE id_publi = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssi", $nome, $legenda, $imagem, $id_publi);

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
?>   -->
<!doctype html>
<html lang="pt-br">

<head>
    <title>Editar Perfil</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../CSS/Editar_Perfil.css" type="text/css">
</head>

<body>
    <header>
    <nav class="navbar navbar-expand-lg" id="navnav">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">UNA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="./Tela_Inicial.php.">Home
            </a></li>

          </ul>
        </div>
      </div>
    </nav>
  </header>

    <div>
    <main class="container ">
        <h1>Editar postagens</h1>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
        <?php unset($_SESSION['error_message']);
        endif; ?>
        <form method="POST" action="Editar_publicacoes.php?id=<?php echo $id_publi; ?>" enctype="multipart/form-data">
            <div class="divimg d-flex" onclick="abrirArquivo()">
                
                <img src="<?php echo htmlspecialchars($user['imagem'])?>" class="imgong" alt="">
                <i class="bi bi-gear-fill"></i>
            </div>
            <input type="file" name="imagem" id="arquivo" hidden>
            <div class="row">
            <div class="col mb-6">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']) ?>" required>
            </div>
            <div class="col mb-6">
                <label for="email">Legenda</label>
                <input type="text" id="legenda" name="legenda" class="form-control" value="<?= htmlspecialchars($user['legenda']) ?>" required>
            </div>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </main>
    <script src="../JS/Imagem_mudar.js"></script>
</body>

</html>