<?php
session_start();
require 'Conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['id_ong'])) {
  echo '<script>alert("Você precisa estar logado para acessar esta página.");</script>';
  echo '<script>window.location.href = "Login.php";</script>';
  exit;
}

$id_usuario = $_SESSION['id_usuario'] ?? null;
$id_ong = $_SESSION['id_ong'] ?? null;
$tipo_usuario = $_SESSION['tipo'] ?? null; // 'usuario', 'ong' ou outro tipo definido
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feed de Publicações</title>
  <link rel="stylesheet" href="../CSS/post.css">
</head>

<body>
  <div class="instagram-layout">
    <aside class="sidebar">
      <div class="menu">
        <a href="../index.html" class="menu-item">
          <img src="../IMG/casa.png" alt="Home" class="menu-icon">
          <span>Página inicial</span>
        </a>
        <a href="Pesquisa_usuario.php" class="menu-item">
          <img src="../IMG/usuarios.png" alt="Pesquisar Usuários" class="menu-icon">
          <span>Pesquisar Usuários</span>
        </a>
        <a href="Pesquisa_ONG.php" class="menu-item">
          <img src="../IMG/ong.png" alt="Pesquisar ONGs" class="menu-icon">
          <span>Pesquisar ONGs</span>
        </a>
        <?php if ($tipo_usuario === 'ong'): ?>
          <a href="Adicionar_Postagens.php" class="menu-item">
            <img src="../IMG/casa.png" alt="Adicionar" class="menu-icon">
            <span>Adicionar postagens</span>
          </a>
          <a href="Perfil_ONG.php" class="menu-item">
            <img src="../IMG/PERF.png" alt="Perfil ONG" class="menu-icon">
            <span>Perfil</span>
          </a>
        <?php elseif ($tipo_usuario === 'usuario'): ?>
          <a href="Perfil_Usuario.php" class="menu-item">
            <img src="../IMG/PERF.png" alt="Perfil Usuário" class="menu-icon">
            <span>Perfil</span>
          </a>
        <?php endif; ?>
        <a href="Logout.php" class="menu-item logout-item">
          <img src="../IMG/sair-alt.png" alt="Logout" class="menu-icon">
          <span>Sair</span>
        </a>
      </div>
    </aside>

    <main class="post-container">
      <h2 style="text-align: center;">Bem-Vindo à Una!</h2>
      <h4 style="text-align: center;">Feed de Publicações</h4>

      <?php
      // Código para exibir as publicações
      $sql = "SELECT publicacoes.nome AS nome_postagem, 
                     publicacoes.legenda, 
                     publicacoes.imagem, 
                     ongs.nome AS nome_ong, 
                     ongs.id_ong 
              FROM publicacoes 
              INNER JOIN ongs ON publicacoes.id_ong = ongs.id_ong 
              ORDER BY publicacoes.id_publi DESC";
      $stmt = $conn->prepare($sql);

      if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        $publicacoes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
      } else {
        echo '<p style="text-align: center;">Erro ao carregar publicações. Tente novamente mais tarde.</p>';
        $publicacoes = [];
      }
      ?>

      <?php if (empty($publicacoes)): ?>
        <p style="text-align: center;">Não há publicações no momento.</p>
      <?php else: ?>
        <?php foreach ($publicacoes as $publicacao): ?>
          <div class="post">
            <a  class ="menu-item" href="Perfil_ONG.php?id_ong=<?php echo htmlspecialchars($publicacao['id_ong']); ?>">
              <div class="post-header">
                <img src="../IMG/logo.png" alt="Profile" class="profile-img">
                <div class="post-info">
                  <span class="profile-name"><?php echo htmlspecialchars($publicacao['nome_ong']); ?></span>

                </div>
              </div>
            </a>
            <div class="post-image">
              <img src="<?php echo htmlspecialchars($publicacao['imagem']); ?>" alt="Imagem da publicação">
            </div>
            <div class="post-footer">
              <p class="description"><?php echo htmlspecialchars($publicacao['legenda']); ?></p>
            </div>
          </div>
          <br>
        <?php endforeach; ?>
      <?php endif; ?>
    </main>
  </div>
</body>

</html>
