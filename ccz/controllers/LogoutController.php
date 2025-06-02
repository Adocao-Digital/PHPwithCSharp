<?php
    session_start();

    // Remove dados da sessão e destrói
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    // Remove cookie de token (se estiver usando cookie)
    setcookie("session_token", "", time() - 3600, "/");

    // Redireciona para página de login
    header("Location: /ccz/login");
    exit;

?>