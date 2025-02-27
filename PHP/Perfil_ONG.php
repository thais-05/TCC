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
$query = "SELECT nome, email_ong, telefone, cnpj, rede_social, link, descricao, cebas FROM ongs WHERE id_ong = ?";
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
$ong['cebas'] = defaultIfNull($ong['cebas']);


// Verifica se a ONG acessada é a mesma que está logada
$edicaoPermitida = isset($_SESSION['id_ong']) && $_SESSION['id_ong'] == $id_ong;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bem-vindo à ONG <?php echo htmlspecialchars($ong['nome']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/62c925f2a3.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../CSS/Perfil_ONG.css">
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #0f7356;">
      <div class="container-fluid">
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
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        
                </ul>
            </div>
        <div class="collapse navbar-collapse margemalterar" id="navbarContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?php if ($edicaoPermitida): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="perfilDropdown" data-bs-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false" style="color: oldlace;">Alterar Perfil</a>
                <div class="dropdown-menu" aria-labelledby="perfilDropdown">
                  <a class="dropdown-item" href="Editar_perfil_ONG.php">Editar Perfil</a>
                  <a class="dropdown-item" href="Excluir_perfil_ong.php">Excluir Perfil</a>
                </div>
              </li>
            <?php endif; ?>
          </ul>
          <div class="d-flex align-items-center ms-auto">
            
            <a class="navbar-brand" href="#">
              <img src="../IMG/logo_una.PNG" alt="Logo UNA" id="logo">
            </a>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <div class="container mt-5">
      <div class="text-center">
        <h1>Bem-vindo à ONG <?php echo htmlspecialchars($ong['nome']); ?></h1>
              <br>
      
      <div class="section texto">
        <h3 class="letra">Nossa Missão:</h3>
        <br>
        <p>Na <?php echo htmlspecialchars($ong['nome']);?>, <?php echo htmlspecialchars($ong['descricao']);?></p>
      </div class="dados">
      <?php if ($edicaoPermitida): ?>
        <h3>Seus dados:</h3>
        <p>Nome: <?php echo htmlspecialchars($ong['nome']); ?></p>
        <p>Email: <?php echo htmlspecialchars($ong['email_ong']); ?></p>
        <p>Telefone: <?php echo htmlspecialchars($ong['telefone']); ?></p>
        <p>CNPJ: <?php echo htmlspecialchars($ong['cnpj']); ?></p>
        
      <?php endif; ?>
      </div>
    </div>

    <!-- Exibição das postagens -->
    <div class="container my-5">
      <h3>Nossas postagens:</h3>
      <div class="row">
        <?php
        $query_posts = "SELECT nome, legenda, imagem FROM publicacoes WHERE id_ong = ? ORDER BY id_publi DESC";
        $stmt = $conn->prepare($query_posts);

        if (!$stmt) {
          die("Erro na preparação da consulta de postagens: " . $conn->error);
        }

        $stmt->bind_param("i", $id_ong);
        $stmt->execute();
        $result_posts = $stmt->get_result();

        if ($result_posts->num_rows > 0) {
          while ($post = $result_posts->fetch_assoc()) {
        ?>
            <div class="col-md-4 mb-3">
              <div class="card">
                <img src="<?php echo htmlspecialchars($post['imagem']); ?>" class="card-img-top"
                  alt="Imagem da postagem">
                <div class="card-body">
                  <h5 class="card-title">Postagem</h5>
                  <p class="card-text">Legenda: <?php echo htmlspecialchars($post['legenda']); ?></p>
                </div>
              </div>
            </div>
        <?php
          }
        } else {
          echo '<p>Sem postagens no momento.</p>';
        }
        $stmt->close();
        ?>
      </div>
    </div>

    <!-- Contato -->
    <div class="container my-5">
      <h3 class="letra" id="contato">Contato:</h3>
      <div class="row">
        <div class="col-12 col-md-4 mb-4">
          <div class="card h-100">
            <div class="card-body cad">
              <h5 class="card-title">Instagram</h5>
              <p class="card-text">@<?php echo htmlspecialchars($ong['rede_social']); ?></p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
          <div class="card h-100">
            <div class="card-body cad">
              <h5 class="card-title">WhatsApp</h5>
              <p class="card-text"><?php echo htmlspecialchars($ong['telefone']); ?></p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4 mb-4">
          <div class="card h-100">
            <div class="card-body cad">
              <h5 class="card-title">Website</h5>
              <p class="card-text"><a href="<?php echo htmlspecialchars($ong['link']); ?>" target="_blank">Visite
                  nosso site</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-dark text-light text-center py-4">
    <p>&copy; 2024 UNA. Todos os direitos reservados.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>