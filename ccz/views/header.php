<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<header>
    <div class="head2"></div>
</header>

<nav class="navbar navbar-expand-xl bg-body-tertiary">
  <div class="container-fluid">
    <nav class="navbar bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="/ccz/">
          <img alt="Logo" class="d-inline-block align-text-top">
        </a>
      </div>
    </nav>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="/ccz/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/ccz/sobre">Sobre nós</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/ccz/fale">Fale conosco</a>
        </li>

        <?php
        // Verifica se o usuário está logado e é ADMIN
        if (isset($_SESSION['usuario_email']) && isset($_SESSION['usuario_role']) && $_SESSION['usuario_role'] === 'ADM') {
            echo "<li class='nav-item'>
                    <a class='nav-link' href='http://localhost:5133/' target='_blank'>Painel de Controle</a>
                  </li>";
        }
        ?>
      </ul>

      <div class="container c-button">
        <a href="/ccz/adotar" class="btn btn-success max-width">Quero adotar</a>
        <a href="/ccz/ajudar" class="btn btn-success max-width">Ajudar</a>

        <?php
        if (isset($_SESSION['usuario_email'])) {
            echo "<a href='/ccz/logout' class='btn btn-success max-width'>Sair</a>";
        } else {
            echo "<a href='/ccz/login' class='btn btn-success max-width'>Entrar</a>";
        }
        ?>
      </div>
    </div>
  </div>
</nav>
