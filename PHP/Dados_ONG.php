<?php
session_start();

require_once 'Conexao.php';

// Obtém o ID da ONG da URL ou da sessão
$id_ong = $_GET['id_ong'] ?? ($_SESSION['id_ong'] ?? null);

if (!$id_ong) {
  echo '<script>alert("Erro: ONG não especificada. Por favor, faça login.");</script>';
  echo '<script>window.location.href = "Login.php";</script>';
  exit;
}

// Consulta SQL para buscar os dados da ONG
$query = "SELECT nome, email_ong, telefone, cnpj, rede_social, link, descricao, cebas, cep, endereco FROM ongs WHERE id_ong = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
  die("Erro na preparação da consulta: " . $conn->error);
}

$stmt->bind_param("i", $id_ong);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo '<script>alert("Erro: ONG não encontrada.");</script>';
  echo '<script>window.location.href = "Tela_Inicial.php";</script>';
  exit;
}

$ong = $result->fetch_assoc();
$stmt->close();

// Tratamento para valores nulos
function defaultIfNull($value, $default = "N/A")
{
  return $value ?? $default;
}

$ong['nome'] = defaultIfNull($ong['nome']);
$ong['descricao'] = defaultIfNull($ong['descricao']);
$ong['email_ong'] = defaultIfNull($ong['email_ong']);
$ong['telefone'] = defaultIfNull($ong['telefone']);
$ong['cnpj'] = defaultIfNull($ong['cnpj']);
$ong['rede_social'] = defaultIfNull($ong['rede_social']);
$ong['link'] = defaultIfNull($ong['link']);
$ong['cep'] = defaultIfNull($ong['cep']);
$ong['cebas'] = defaultIfNull($ong['cebas']);


// Verifica se a ONG acessada é a mesma que está logada
$edicaoPermitida = isset($_SESSION['id_ong']) && $_SESSION['id_ong'] == $id_ong;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Dados_ong.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <title>Dados ong</title>
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
            <li class="nav-item"><a class="nav-link" href="../PHP/Tela_Inicial.php">Home
            </a></li>

          </ul>
        </div>
      </div>
    </nav>
  </header>

    <main class="centralizar">
    <section class="dados">
        <div>
        <h2>Dados da ong</h2>
        <div class="mt-2">
            
            <h4 class="text-start">Imagem perfil:</h4>
            
            <img src="../IMG/perfil1.jpg" alt="" class="imagem  mt-2">
            </div>
            <h4 class="mt-2 text-start">Seus Dados:</h4>
            <div class="d-flex text-start gap-5 align-items-center dadostexto centro">
            <div>
            <h6>Nome: <?php echo htmlspecialchars($ong['nome']); ?></h6>
            <h6>Email: <?php echo htmlspecialchars($ong['email_ong']); ?></h6>
            </div>
            <div>
            <h6>Telefone: <?php echo htmlspecialchars($ong['telefone']); ?></h6>
            <h6>CNPJ: <?php echo htmlspecialchars($ong['cnpj']); ?></h6>
            </div>
            <div>
            <h6>CEP: <?php echo htmlspecialchars($ong['cep']); ?></h6>
            <h6>Endereço: <?php echo htmlspecialchars($ong['endereco']); ?></h6>
            </div>
            </div>
            <h4 class= "mt-2">Descrição</h4>
            <div class="descri">
            <p><?php echo htmlspecialchars($ong['descricao']); ?></p>
            </div>
            <a href="./Editar_perfil_ONG.php" class="btn btn-dark">Editar</a>
            </div>
    </section>
    </main>
</body>
</html>