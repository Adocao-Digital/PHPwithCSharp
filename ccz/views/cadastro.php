<?php
if (isset($_SESSION['nome'])) {
    header("Location: /ccz/");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça seu cadastro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style_cadastro.css">
    <link rel="stylesheet" href="css/style-footer.css">
    <link rel="stylesheet" href="css/style-header.css">
    <link rel="icon" type="image/x-icon" href="img/dogo-argentino.png">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200..1000&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Início do cabeçalho -->
    <?php require_once 'header.php'; ?>
    <!-- Fim do cabeçalho -->

    <div class="caixa-total">
        <div class="caixa-direita">
            <form action="/ccz/cadastro" method="post" enctype="multipart/form-data">
                <div class="form">
                    <h1>Registrar-se</h1>
                    <?php if (!empty($_SESSION['erro_cadastro'])): ?>
                        <div class="alert alert-danger my-3">
                            <?= $_SESSION['erro_cadastro']; unset($_SESSION['erro_cadastro']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['mensagem'])): ?>
                        <div class="alert alert-success my-3">
                            <?= $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?>
                        </div>
                    <?php endif; ?>

                    <label for="nome" class="padding">Nome Completo:
                        <input type="text" id="nome" name="nome" class="estilo-forms" placeholder="Digite seu nome..." required>
                    </label>

                    <label for="cpf">CPF:
                        <input type="text" id="cpf" name="cpf" class="estilo-forms" placeholder="Digite seu CPF..." required>
                    </label>

                    <label for="email">E-mail:
                        <input type="email" id="email" name="email" class="estilo-forms" placeholder="Digite seu e-mail..." required>
                    </label>

                    <label for="senha">Digite sua senha:
                        <input type="password" name="senha" id="senha" class="estilo-forms" placeholder="Digite sua senha..." required>
                    </label>

                    <label for="confirma">Confirme sua senha:
                        <input type="password" id="confirma" name="confirma" class="estilo-forms" placeholder="Confirme sua senha..." required>
                        <span id="escrita" class="error"></span>
                    </label>

                    <label for="telefone">Telefone:
                        <input type="text" id="telefone" name="telefone" class="estilo-forms" pattern="\d{10,11}" placeholder="Digite seu telefone..." required>
                    </label>

                    <!-- <label for="sexo">Sexo:
                        <select name="sexo" required class="sexoform">
                            <option value="" disabled selected>Selecione...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Feminino">Feminino</option>
                            <option value="Não-binário">Não-binário</option>
                            <option value="NULL">Prefiro não informar</option>
                        </select>
                    </label> -->

                    <label for="datanasc">Selecione sua data de nascimento:<br>
                        <input type="date" name="datanasc" id="datanasc" class="estilo-forms" required>
                    </label>

                    <label for="rua">Rua:
                        <input type="text" id="rua" name="rua" class="estilo-forms" placeholder="Digite sua rua...">
                    </label>

                    <label for="numero">Número:
                        <input type="text" id="numero" name="numero" class="estilo-forms" placeholder="Digite o número...">
                    </label>

                    <label for="bairro">Bairro:
                        <input type="text" id="bairro" name="bairro" class="estilo-forms" placeholder="Digite o bairro...">
                    </label>

                    <label for="cidade">Cidade:
                        <input type="text" id="cidade" name="cidade" class="estilo-forms" placeholder="Digite a cidade...">
                    </label>

                    <label for="estado">Estado:
                        <input type="text" id="estado" name="estado" class="estilo-forms" placeholder="Digite o estado...">
                    </label>

                    <label for="cep">CEP:
                        <input type="text" id="cep" name="cep" class="estilo-forms" placeholder="Digite o CEP...">
                    </label>

                    <label for="foto">Foto de perfil:
                        <input type="file" id="foto" name="foto" accept="image/*" class="estilo-forms">
                    </label>

                    <div class="cad">
                        <button type="submit">Cadastrar</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="caixa-esquerda">
            <div class="titulo-cadastro">
                <h1>Já se registrou?</h1>
            </div>
            <h2>Seja bem-vindo de volta! <br /> Venha fazer a diferença <br />conosco.</h2>
            <div class="botao-entrar">
                <a href="/ccz/login">Entrar</a>
            </div>
        </div>
    </div>

    <script src="js/script-ativo.js"></script>
    <script src="js/script-cad.js"></script>
    <script>
        document.querySelector("form").addEventListener("submit", function (e) {
            const senha = document.getElementById("senha").value;
            const confirma = document.getElementById("confirma").value;

            if (senha !== confirma) {
                e.preventDefault();
                document.getElementById("escrita").textContent = "As senhas não coincidem.";
            }
        });
    </script>
</body>

<footer>
    <?php require_once 'footer.html'; ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</html>
