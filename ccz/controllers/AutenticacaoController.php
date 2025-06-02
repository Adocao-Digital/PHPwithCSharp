<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../models/conexaomongo.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $url = "http://localhost:5133/api/auth/login"; // ajuste para a rota correta da API

    $dados = [
        'Email' => $email,
        'Senha' => $senha
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));

    $resposta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        die("Erro ao se conectar com o servidor de autenticação: " . curl_error($ch));
    }

    curl_close($ch);

    $resultado = json_decode($resposta, true);

    if ($httpCode == 200 && isset($resultado['token'])) {
        // Armazena token JWT na sessão
        $_SESSION['jwt'] = $resultado['token'];
        $_SESSION['usuario_email'] = $email;

        // Opcional: armazene também dados do usuário se retornados pela API
        if (isset($resultado['usuario'])) {
            $_SESSION['usuario_id'] = $resultado['usuario']['id'] ?? null;
            $_SESSION['usuario_nome'] = $resultado['usuario']['nome'] ?? null;
            $_SESSION['usuario_role'] = $resultado['usuario']['role'] ?? 'USER';
        }

        // Salva token na collection de sessões no MongoDB para validação futura (opcional)
        $conexao = new ConexaoMongo();
        $db = $conexao->getDB();

        $expiracao = new MongoDB\BSON\UTCDateTime((time() + 3600) * 1000); // 1 hora

        $db->sessions->insertOne([
            'token' => $resultado['token'],
            'email' => $email,
            'role' => $_SESSION['usuario_role'],
            'expires_at' => $expiracao
        ]);

        // Cria cookie para controle (opcional)
        setcookie("session_token", $resultado['token'], [
            'expires' => time() + 3600,
            'path' => '/',
            'domain' => 'localhost', // configure seu domínio aqui
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        header("Location: /ccz");
        exit;

    } else {
        $_SESSION['erro_login'] = $resultado['mensagem'] ?? "Credenciais inválidas.";
        header("Location: /ccz/login");
        exit;
    }
}
?>
