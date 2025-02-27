<?php
// Inicia a sessão para autenticação
session_start();

// Inclui a conexão com o banco de dados
require_once 'Conexao.php'; // Ajuste o nome para o caminho correto

// Obtém o ID do usuário da URL ou da sessão
$id_usuario = $_GET['id_usuario'] ?? ($_SESSION['id_usuario'] ?? null);

if (!$id_usuario) {
    echo '<script>alert("Erro: usuário não especificado. Por favor, faça login.");</script>';
    echo '<script>window.location.href = "Login.php";</script>';
    exit;
}

// Consulta SQL para buscar os dados do usuário
$query = "SELECT nome, email, telefone FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<script>alert("Erro: usuário não encontrado.");</script>';
    echo '<script>window.location.href = "Login.php";</script>';
    exit;
}

// Obtém os dados do usuário
$usuario = $result->fetch_assoc();
$stmt->close();

// Se o usuário acessado for diferente do logado, desativa opções de edição/exclusão
$edicaoPermitida = isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == $id_usuario;
?>

<!doctype html>
<html lang="pt-br">

<head>
    <title>Perfil</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://kit.fontawesome.com/62c925f2a3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../CSS/perfil_usuario.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top tamanho" style="background-color: #0f7356;">
        <div class="container-fluid tamanho">
            <a class="navbar-brand" href="Tela_Inicial.php" style="color: oldlace;">UNA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse tamanho" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <a href="Tela_Inicial.php"><i class="fa fa-home margemnavbar " aria-hidden="true"></i></a>
                 
                </ul>
                <div class="margemsair" style="margin-right: 30px;"></div>
            </div>
            <div class="d-flex align-items-center ms-auto">
                <a class="navbar-brand " href="#">
                    <img src="../IMG/logo_una.PNG" alt="Logo UNA" id="logo">
                </a>
            </div>
        </div>
    </nav>
    <div class="cor">
        <div class="tudo" style="height: 100%; margin-top: 8vh; max-height: 100%;">
            <img src="../IMG/una3.o.png" class="imgtudo" alt="">
            <div class="perfil1">
                <div class="informacoes lado">
                    <img src="../IMG/PERF.png" class="imgperfil" alt="">
                    <h1 class="nome"><?php echo htmlspecialchars($usuario['nome']); ?></h1>
                    <p class="doador">Doador(a) e colaborador(a) da UNA</p>
                    <br>
                    <?php if ($edicaoPermitida): ?>
                        <div class="campobotao">
                            <div class="botoes">
                                <a href="../PHP/Editar_Perfil_Usuario.php"><button class="botao2 botao">
                                        <p class="legbotao">Editar Perfil</p>
                                    </button></a>
                                <form method="POST" action="Perfil_Usuario.php" style="display: inline;">
                                    <button type="submit" name="excluir" class="botao2 botao"
                                        onclick="return confirm('Tem certeza que deseja excluir seu perfil? Esta ação não pode ser desfeita.')">
                                        <p class="legbotao">Excluir Perfil</p>
                                    </button>
                                </form>
                            </div>
                            <br>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="dados">
                    <h1 class="titulo">Seus dados:</h1>
                    <p class="fonte magemtexto">Nome: <?php echo htmlspecialchars($usuario['nome']); ?></p>
                    <p class="fonte">Email: <?php echo htmlspecialchars($usuario['email']); ?></p>
                    <p class="fonte">Telefone: <?php echo htmlspecialchars($usuario['telefone']); ?></p>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
</body>

</html>