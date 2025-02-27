<?php
session_start();
require 'Conexao.php';

// Verifica se o usuário está logado como ONG
if (!isset($_SESSION['id_ong'])) {
    echo '<script>alert("Você precisa estar logado para realizar essa ação.");</script>';
    echo '<script>window.location.href = "Login.php";</script>';
    exit;
}

$id_ong = $_SESSION['id_ong'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = trim($_POST['nome'] ?? '');
        $legenda = trim($_POST['legenda'] ?? '');
        $imagem = $_FILES['imagem'] ?? null;

        if (empty($nome) || empty($legenda) || !$imagem) {
            throw new Exception("Todos os campos são obrigatórios.");
        }

        // Verifica se o diretório de upload existe
        $diretorio = '../IMG/postagens/';
        if (!file_exists($diretorio)) {
            mkdir($diretorio, 0777, true);
        }

        // Faz o upload da imagem
        $extensao = pathinfo($imagem['name'], PATHINFO_EXTENSION);
        $novoNome = uniqid('imagem_') . '.' . $extensao;
        $caminhoDestino = $diretorio . $novoNome;

        if (!move_uploaded_file($imagem['tmp_name'], $caminhoDestino)) {
            throw new Exception("Erro ao fazer upload da imagem.");
        }

        // Insere a postagem no banco de dados
        $sql_insert_post = "INSERT INTO publicacoes (id_ong, nome, legenda, endereco, imagem, criado_em) 
                            VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql_insert_post);
        $endereco = ''; // Atualize o campo 'endereco' conforme necessário
        $stmt->bind_param("issss", $id_ong, $nome, $legenda, $endereco, $caminhoDestino);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao salvar a postagem: " . $stmt->error);
        }

        echo '<script>alert("Postagem realizada com sucesso.");</script>';
        echo '<script>window.location.href = "Tela_Inicial.php";</script>';
    } catch (Exception $e) {
        echo '<script>alert("Erro: ' . $e->getMessage() . '");</script>';
        echo '<script>window.history.back();</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adicionar Postagem</title>
  <link rel="stylesheet" href="../CSS/adicionar_postagens.css">
</head>

<body>
  <div class="card">
    <h2>Adicionar Postagem</h2>
    <br>
    <form id="postForm" action="" method="POST" enctype="multipart/form-data">
      <label for="nome-ong">
        <span>Nome da ONG</span>
        <input type="text" id="nome" name="nome" placeholder="Digite o nome da ONG..." required>
      </label>
      <label for="foto">
        <span>Adicionar uma foto</span>
        <input type="file" id="imagem" name="imagem" required>
      </label>
      <label for="legenda">
        <span>Legenda</span>
        <textarea id="legenda" name="legenda" placeholder="Escreva uma legenda para sua postagem..." required></textarea>
      </label>
      <div class="buttons">
        <button type="submit" class="btn btn-success" id="publishBtn">Publicar</button>
        <button type="button" class="btn btn-cancel" id="cancelBtn">Cancelar</button>
      </div>
    </form>
  </div>
  <script src="../JS/adicionar_postagens.js"></script>
</body>

</html>
