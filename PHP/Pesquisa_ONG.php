<?php
include 'conexao.php'; // Conexão com o banco de dados
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <title>Pesquisa de ONGs</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Sidebar */
        #sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background-color: #0f7356;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        #sidebar .brand {
            font-size: 1.5rem;
            font-weight: bold;
            padding: 20px;
            text-align: center;
            color: white;
            border-bottom: 1px solid #ffffff40;
        }

        #sidebar .nav-link {
            font-size: 1rem;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: white;
        }

        #sidebar .nav-link:hover {
            background-color: #1c8c6f;
            text-decoration: none;
        }

        #sidebar .nav-link img {
            width: 24px;
            height: 24px;
        }

        #sidebar .logout {
            margin-top: auto;
            border-top: 1px solid #ffffff40;
        }

        main {
            margin-left: 250px;
            padding: 20px;
        }

        /* Centraliza os cards */
        .ong-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        /* Estilo dos cards */
        .ong-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s;
        }

        .ong-card:hover {
            transform: translateY(-5px);
        }

        .ong-card img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .ong-card h3 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .ong-card p {
            font-size: 14px;
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .btn {
            width: 150px;
            height: 40px;
            border-radius: 4px;
            background-color: #147a60;
            color: white;
            border: none;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #105c4a;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="brand">UNA</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="Tela_Inicial.php" class="nav-link">
                        <img src="../IMG/home.png" alt="Início"> Início
                    </a>
                </li>
                <li class="nav-item">
                    <a href="Pesquisa_ONG.php" class="nav-link">
                        <img src="../IMG/ong.png" alt="ONGs"> ONGs
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="Pesquisa_Usuario.php" class="nav-link">
                        <img src="../IMG/usuario.png" alt="Usuários"> Usuários
                    </a>
                </li>
                <li class="nav-item logout">
                    <a href="logout.php" class="nav-link">
                        <img src="../IMG/logout.png" alt="Sair"> Sair
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main>
            <header class="bg-light p-3 rounded">
                <h4>Pesquisa de ONGs</h4>
            </header>

            <!-- Lista de ONGs -->
            <section class="my-4">
                <div class="ong-container">
                    <?php
                    // Consulta para obter todas as ONGs
                    $sql = "SELECT id_ong, nome, logo, descricao FROM ongs";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($ong = $result->fetch_assoc()) {
                            $id_ong = htmlspecialchars($ong['id_ong']);
                            $nome = htmlspecialchars($ong['nome'] ?? 'Nome não disponível');
                            $logo = htmlspecialchars($ong['cebas'] ?? '../IMG/default-logo.png');
                            $descricao = htmlspecialchars($ong['descricao'] ?? 'Descrição não disponível');

                            echo '
                            <div class="ong-card">
                                <img src="../IMG/una.PNG">
                                <h3>' . $nome . '</h3>
                                <a href="Perfil_ONG.php?id_ong=' . $id_ong . '" class="btn">Conhecer</a>
                            </div>';
                        }
                    } else {
                        echo '<p class="text-center">Nenhuma ONG cadastrada.</p>';
                    }

                    $conn->close();
                    ?>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
