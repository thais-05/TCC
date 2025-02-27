<!doctype html>
<html lang="pt-br">

<head>
    <title>Cadastro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/Cadastro.css">
    <link rel="stylesheet" href="../CSS/popup.css">
    <script>
        function alterarCampos(tipo) {
            document.querySelectorAll('.campo-dinamico').forEach(campo => campo.style.display = 'none');
            const tipoSelecionado = document.getElementById('campos-' + tipo);
            if (tipoSelecionado) {
                tipoSelecionado.style.display = 'block';
            }
        }

        function formatarTelefone(v) {
            v = v.replace(/\D/g, "");
            v = v.replace(/^(\d{2})(\d)/, "($1) $2");
            v = v.replace(/(\d{5})(\d)/, "$1-$2");
            return v.substring(0, 15);
        }

        function formatarCNPJ(v) {
            v = v.replace(/\D/g, "");
            v = v.replace(/^(\d{2})(\d)/, "$1.$2");
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
            v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
            v = v.replace(/(\d{4})(\d)/, "$1-$2");
            return v.substring(0, 18);
        }

        function formatarCEP(v) {
            v = v.replace(/\D/g, "");
            v = v.replace(/(\d{5})(\d)/, "$1-$2");
            return v.substring(0, 9);
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
    </script>
</head>

<body>
    <main class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-6 text-center" id="imagem">
            <img src="../IMG/Complemento.png" alt="Imagem" class="img-fluid imagem">
        </div>

        <div class="col-md-6 d-flex justify-content-center" id="cadastro">
            <div class="cadastro p-4 shadow-lg rounded">
                <h1 class="titlogin mb-4">Cadastro</h1>
                <div class="mb-3">
                    <label for="tipoCadastro" class="form-label">Tipo de Cadastro:</label>
                    <select id="tipoCadastro" name="tipo" class="form-select" onchange="alterarCampos(this.value)" required>
                        <option value="">Selecione...</option>
                        <option value="usuario">Usuário</option>
                        <option value="ong">ONG</option>
                    </select>
                </div>

                <!-- Formulário Usuário -->
                <div id="campos-usuario" class="campo-dinamico" style="display: none;">
                    <form method="POST" action="Processa_Cadastro.php">
                        <input type="hidden" name="tipo" value="usuario">
                        <input type="text" class="form-control mb-3" name="nome" placeholder="Nome completo" required>
                        <input type="email" class="form-control mb-3" name="email" placeholder="Email válido" required>
                        <input type="text" class="form-control mb-3" name="telefone" placeholder="Telefone (XX) XXXXX-XXXX" minlength="10" maxlength="15" oninput="this.value = formatarTelefone(this.value);" required>
                        <p >senha com no mínimo 8 digitos </p>
                        <input type="password" class="form-control mb-3" name="senha" placeholder="Senha segura" minlength="8" maxlength="16" required>
                        <input type="password" class="form-control mb-3" name="confirma_senha" placeholder="Confirmar senha" minlength="8" maxlength="16" required>
                        <button type="submit" class="btn btn-dark w-100">Cadastrar</button>
                    </form>
                </div>

                <!-- Formulário ONG -->
                <div id="campos-ong" class="campo-dinamico" style="display: none;">
                    <form method="POST" action="Processa_Cadastro.php" enctype="multipart/form-data">
                        <input type="hidden" name="tipo" value="ong">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control mb-2" name="nome" placeholder="Nome da ONG" required>
                                <input type="email" class="form-control mb-2" name="email_ong" placeholder="Email válido" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control mb-2" name="cnpj" placeholder="CNPJ (XX.XXX.XXX/XXXX-XX)" minlength="14" maxlength="18" oninput="this.value = formatarCNPJ(this.value);" required>
                                <input type="text" class="form-control mb-2" name="telefone" placeholder="Telefone(Whatsapp) (XX) XXXXX-XXXX" minlength="10" maxlength="15" oninput="this.value = formatarTelefone(this.value);" required>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control mb-2" name="cep" placeholder="CEP (XXXXX-XXX)" minlength="8" maxlength="9" oninput="this.value = formatarCEP(this.value);">
                                <p id="senha">senha com no mínimo 8 digitos </p>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control mb-2" name="endereco" placeholder="Endereço">
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control mb-2" name="endereco_numero" placeholder="Nº" maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                
                                <input type="password" class="form-control mb-2" name="senha" placeholder="Senha segura" minlength="8" maxlength="16" required>
                                
                            </div>
                            <div class="col-md-6">
                                <input type="password" class="form-control mb-2" name="confirma_senha" placeholder="Confirmar senha" minlength="8" maxlength="16" required>
                            </div>
                        </div>

                        <textarea class="form-control mb-2" name="descricao" placeholder="Breve descrição da ONG" rows="3"></textarea>
                        <input type="file" class="form-control mb-2" name="cebas" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control mb-2" name="rede_social" placeholder="Rede Social (URL válida)">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control mb-2" name="link" placeholder="Link adicional (URL válida)">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Cadastrar</button>
                    </form>
                </div>

                <p class="text-center mt-3" style="color: #D5F5E3;">
                    Já cadastrado? <a href="Login.php" class="text-decoration-none" style="color: #3897f0;">Entre aqui.</a>
                </p>
                </form>
            </div>
        </div>
        </div>
    </main>
</body>

</html>