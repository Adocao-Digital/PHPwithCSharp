<?php
if (!isset($_SESSION)) {
    session_start();
}

// Evita erros caso as variáveis não estejam definidas
$mensagem = $mensagem ?? "";
$msg = $msg ?? ["", ""];

if (UsuarioController::usuarioAutenticado()) {
    header("Location: /ccz/");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style-footer.css">
    <link rel="stylesheet" href="css/style-header.css">
    <link rel="stylesheet" href="css/style.login.css">
</head>
<body>
    <!-- Início do cabeçalho -->
    <?php require_once 'header.php'; ?>
    <!-- Fim do cabeçalho -->

    <main>
        <div class="conteudo-login">
            <div class="login">
                <h1>Fazer Login</h1>
                <form class="formulario" action="/ccz/controllers/AutenticacaoController.php" method="POST">
                    <?php if (isset($_SESSION['erro_login'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?></div>
                    <?php endif; ?>

                    <div class="controle">
                        <label for="email">
                            <img class="img-person" src="img/usuario.png" alt="Ícone de usuário">
                        </label>
                        <input class="bx" type="email" name="email" id="email" placeholder="Digite seu e-mail..." required>
                    </div>
                    <?php if (!empty($msg[0])): ?>
                        <div style="color:red; font-size:10px;">
                            <?= htmlspecialchars($msg[0]) ?>
                        </div>
                    <?php endif; ?>

                    <div class="controle">
                        <label for="senha">
                            <img class="img-lock" src="img/senha.png" alt="Ícone de senha">
                        </label>
                        <input class="bx" type="password" name="senha" id="senha" placeholder="Digite sua senha..." required>
                        <button type="button" class="mostrar-senha" onclick="toggleSenha()">
                            <img src="img/closed-eye.png" alt="Mostrar/Esconder senha" id="imgSenha">
                        </button>
                    </div>
                    <?php if (!empty($msg[1])): ?>
                        <div style="color:red; font-size:10px;">
                            <?= htmlspecialchars($msg[1]) ?>
                        </div>
                    <?php endif; ?>

                    <input class="controle-login bxlogin" type="submit" value="Login">
                </form>

                <a href="#" class="parag">Esqueci minha senha</a>
                <a href="/ccz/cadastro" class="registro-link">Registrar</a>
            </div>
        </div>
    </main>

    <footer>
        <?php require_once 'footer.html'; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <script>
        function toggleSenha() {
            const senhaInput = document.getElementById("senha");
            const imgSenha = document.getElementById("imgSenha");
            if (senhaInput.type === "password") {
                senhaInput.type = "text";
                imgSenha.src = "img/open-eye.png";
            } else {
                senhaInput.type = "password";
                imgSenha.src = "img/closed-eye.png";
            }
        }
    </script>
</body>
</html>
