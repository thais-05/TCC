<!doctype html>
<html lang="pt-br">

<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../CSS/Login.css" type="text/css">
</head>

<script>

    function formatarCNPJ(v) {
            v = v.replace(/\D/g, "");
            v = v.replace(/^(\d{2})(\d)/, "$1.$2");
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
            v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
            v = v.replace(/(\d{4})(\d)/, "$1-$2");
            return v.substring(0, 18);
        }

    function showPopup(message) {
        const popup = document.getElementById("popupError");
        const errorMessage = document.getElementById("errorMessage");
        errorMessage.textContent = message;
        popup.classList.remove("d-none");
    }

    function closePopup() {
        const popup = document.getElementById("popupError");
        popup.classList.add("d-none");
    }

    document.addEventListener("DOMContentLoaded", function() {
        const tipoSelecionado = document.getElementById("tipoSelecionado");
        const submitButton = document.querySelector("button[type='submit']");

        // Ocultar o botão inicialmente
        submitButton.style.display = "none";

        // Monitorar mudanças no tipo selecionado
        tipoSelecionado.addEventListener("change", function() {
            if (tipoSelecionado.value === "") {
                // Se nada for selecionado, oculte o botão
                submitButton.style.display = "none";
            } else {
                // Se algo for selecionado, exiba o botão
                submitButton.style.display = "block";
            }
        });
    });
</script>

<body>
    <main class="container min-vh-100 d-flex align-items-center justify-content-center">
        <!-- Coluna de imagem -->
        <section class="col-md-6 text-center" id="imagem">
            <img src="../IMG/Complemento.png" alt="Imagem" class="img-fluid imagem">
        </section>

        <!-- Coluna de formulário -->
        <section class="col-md-6 d-flex justify-content-center" id="login">
            <div class="login p-4 shadow-lg rounded w-100">
                <h1 class="titlogin mb-4">Login</h1>
                <!-- Formulário para login -->
                <form id="formLogin" method="POST" action="Processa_Login.php">
                    <!-- Seleção do tipo -->
                    <div class="mb-3">
                        <label for="tipoSelecionado" class="form-label" style="color: #D5F5E3;">Tipo de Login:</label>
                        <select id="tipoSelecionado" name="tipo" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="usuario">Usuário</option>
                            <option value="ong">ONG</option>
                        </select>
                    </div>

                    <!-- Inputs dinâmicos -->
                    <div id="inputsDinamicos"></div>

                    <p class="text-center mt-3" style="color: #D5F5E3;">
                        Não cadastrado? <a href="Cadastro.php" class="text-decoration-none" style="color: #3897f0;">Se cadastre aqui.</a>
                    </p>
                    <!-- Botão de envio -->
                    <button type="submit" class="btn btn-dark w-100 mb-3 cademail">Entrar</button>
                </form>
                <div id="popupError" class="popup-error d-none">
                    <div class="popup-content">
                        <p id="errorMessage"></p>
                        <button onclick="closePopup()">Fechar</button>
                    </div>
                </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lógica para inputs dinâmicos com base no tipo selecionado
        document.getElementById("tipoSelecionado").addEventListener("change", function() {
            const tipo = this.value;
            const inputsDinamicos = document.getElementById("inputsDinamicos");

            // Limpar campos dinâmicos ao trocar tipo
            inputsDinamicos.innerHTML = "";

            // Adicionar campos específicos conforme o tipo
            if (tipo === "usuario") {
                inputsDinamicos.innerHTML = `
                <div id="inputsGerais">
                    <div class="mb-3">
                        <input type="email" name="email" id="email" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="senha" id="senha" placeholder="Senha" class="form-control" required>
                    </div>
                </div>`;
            } else if (tipo === "ong") {
                inputsDinamicos.innerHTML = `
                <div id="inputsGerais">
                    <div class="mb-3">
                        <input type="email" name="email" id="email" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="senha" id="senha" placeholder="Senha" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="cnpj" id="cnpj" placeholder="CNPJ" class="form-control" oninput="this.value = formatarCNPJ(this.value);" required>
                    </div>
                </div>`;
            }
        });
    </script>
</body>

</html>
