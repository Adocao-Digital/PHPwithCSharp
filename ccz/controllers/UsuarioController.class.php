<?php

if (!isset($_SESSION)) {
    session_start();
}

class UsuarioController
{
    private $apiLoginUrl = "http://localhost:5133/api/auth/login";

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $payload = json_encode([
                'Email' => $email,
                'Senha' => $senha
            ]);

            $ch = curl_init($this->apiLoginUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $data = json_decode($response, true);

                if (isset($data['token'])) {
                    // Armazena também na sessão, se quiser
                    $_SESSION['jwt'] = $data['token'];
                    $_SESSION['usuario_email'] = $email;

                    // Define o cookie para todos os subdomínios de .ccz.local
                    setcookie('auth_token', $data['token'], [
                        'expires' => time() + 3600, // 1 hora
                        'path' => '/',
                        'domain' => 'localhost',
                        'secure' => false,      // true se usar HTTPS
                        'httponly' => true,
                        'samesite' => 'Lax'     // ou 'None' se usar HTTPS
                    ]);

                    header("Location: /ccz/");
                    exit;
                } else {
                    $_SESSION['erro_login'] = "Resposta inesperada do servidor.";
                }
            } elseif ($httpCode === 401) {
                $_SESSION['erro_login'] = "Email ou senha inválidos.";
            } else {
                $_SESSION['erro_login'] = "Erro na comunicação com o servidor de autenticação.";
            }
        }

        require_once "views/login.php";
    }

    public function cadastro()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            try {
                // Cria o objeto usuario (pode continuar usando para sua lógica interna)
                $usuario = new Usuario(
                    trim($_POST['nome']),
                    trim($_POST['email']),
                    trim($_POST['cpf']),
                    $_POST['senha'],
                    new Endereco(
                        trim($_POST['rua']),
                        trim($_POST['numero']),
                        trim($_POST['bairro']),
                        trim($_POST['cidade']),
                        trim($_POST['estado']),
                        trim($_POST['cep'])
                    ),
                    trim($_POST['telefone']),
                    new DateTime($_POST['datanasc']),
                    null,
                    true
                );

                // Monta o array para enviar na requisição, desaninhando o endereço
                $postData = [
                    'Nome' => trim($_POST['nome']),
                    'Email' => trim($_POST['email']),
                    'Cpf' => trim($_POST['cpf']),
                    'Senha' => $_POST['senha'],
                    'Telefone' => trim($_POST['telefone']),
                    'DataNascimento' => $_POST['datanasc'],
                    'Ativo' => 'true',

                    // Campos do endereço desaninhados com pontos
                    'Rua' => trim($_POST['rua']),
                    'Numero' => trim($_POST['numero']),
                    'Bairro' => trim($_POST['bairro']),
                    'Cidade' => trim($_POST['cidade']),
                    'Estado' => trim($_POST['estado']),
                    'Cep' => trim($_POST['cep']),
                ];

                // Verifica se foi enviado arquivo de foto e adiciona CURLFile para upload
                if (!empty($_FILES['foto']['tmp_name']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
                    $postData['FotoUpload'] = new CURLFile(
                        $_FILES['foto']['tmp_name'],
                        $_FILES['foto']['type'],
                        $_FILES['foto']['name']
                    );
                }

                $ch = curl_init("http://localhost:5133/api/auth/register");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

                // Atenção: NÃO configurar Content-Type aqui para multipart, o CURL define automaticamente
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

                $resposta = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
                    header("Location: /ccz/login");
                    exit;
                } else {
                    $erro = json_decode($resposta, true);
                    if (isset($erro['errors']) && is_array($erro['errors'])) {
                        $mensagens = [];
                        foreach ($erro['errors'] as $campo => $msgs) {
                            foreach ($msgs as $msg) {
                                $mensagens[] = "$campo: $msg";
                            }
                        }
                        $_SESSION['erro_cadastro'] = implode("<br>", $mensagens);
                    } else {
                        $_SESSION['erro_cadastro'] = $erro['message'] ?? "Erro ao cadastrar usuário.";
                    }
                }
            } catch (Exception $e) {
                $_SESSION['erro_cadastro'] = "Erro ao processar cadastro: " . $e->getMessage();
            }
        }

        require_once 'views/cadastro.php';
    }



    public function logout()
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        setcookie("session_token", "", time() - 3600, "/");
        header("Location: /ccz/login");
        exit;
    }

    public static function usuarioAutenticado(): bool {
        if (!isset($_SESSION['jwt'])) return false;

        return true;
    }

    public static function obterToken()
    {
        if (!isset($_SESSION)) session_start();
        return $_SESSION['jwt'] ?? null;
    }
}
