<?php

if (!isset($_SESSION)) {
    session_start();
}

class UsuarioController
{
    public function cadastro()
    {
        $msg = array("", "", "", "", "");
        $erro = false;

        if ($_POST) {
            // Validação dos campos
            if (empty($_POST["nome"])) {
                $msg[0] = "Preencha o seu nome";
                $erro = true;
            }

            if (empty($_POST["email"])) {
                $msg[1] = "Preencha o seu e-mail";
                $erro = true;
            } else {
                $usuario = new Usuario();
                $usuario->email = $_POST["email"];
                $usuarioDAO = new UsuarioDAO();
                $retorno = $usuarioDAO->login($usuario);

                if ($retorno) {
                    $msg[1] = "E-mail já cadastrado";
                    $erro = true;
                }
            }

            if (empty($_POST["cpf"])) {
                $msg[4] = "Preencha o CPF";
                $erro = true;
            } else {
                $usuario = new Usuario();
                $usuario->cpf = $_POST["cpf"];
                $usuarioDAO = new UsuarioDAO();
                $retorno = $usuarioDAO->login($usuario);

                if ($retorno) {
                    $msg[4] = "CPF já cadastrado";
                    $erro = true;
                }
            }

            if (empty($_POST["senha"])) {
                $msg[2] = "Preencha a senha";
                $erro = true;
            }

            if (empty($_POST["confirma"])) {
                $msg[3] = "Confirme a senha";
                $erro = true;
            }

            if ($_POST["senha"] !== $_POST["confirma"]) {
                $msg[3] = "Senhas não conferem";
                $erro = true;
            }

            if (!$erro) {
                // Montagem do objeto Usuario
                $usuario = new Usuario();
                $usuario->nome = $_POST["nome"];
                $usuario->email = $_POST["email"];
                $usuario->cpf = $_POST["cpf"];
                $usuario->senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
                $usuario->telefone = $_POST["telefone"];
                $usuario->foto = null; // ou $_POST["foto"] se houver
                $usuario->ativo = true;
                $usuario->dataNascimento = new DateTime($_POST["datanasc"]);

                // Montagem do endereço
                $usuario->endereco = new Endereco(
                    $_POST["rua"],
                    $_POST["numero"],
                    $_POST["bairro"],
                    $_POST["cidade"],
                    $_POST["estado"],
                    $_POST["cep"]
                );

                // Inserir no banco
                $usuarioDAO = new UsuarioDAO();
                $msg[4] = $usuarioDAO->cadastro($usuario); // usando método que já trata duplicidade e hash
            }
        }

        require_once "views/cadastro.php";
    }

    public function login()
    {
        $msg = array("", "");
        $erro = false;
        $mensagem = "";

        if ($_POST) {
            if (empty($_POST["email"])) {
                $msg[0] = "Informe o E-mail";
                $erro = true;
            }

            if (empty($_POST["senha"])) {
                $msg[1] = "Informe a senha";
                $erro = true;
            }

            if (!$erro) {
                $usuario = new Usuario();
                $usuario->email = $_POST["email"];
                $usuario->senha = $_POST["senha"];

                $usuarioDAO = new UsuarioDAO();
                $retorno = $usuarioDAO->login($usuario);

                if ($retorno && password_verify($_POST["senha"], $retorno['senha'])) {
                    $_SESSION["idusuario"] = (string) $retorno['_id'];
                    $_SESSION["nome"] = $retorno['nome'];
                    $_SESSION["email"] = $retorno['email'];
                    $_SESSION["perfil"] = "Usuario";

                    $redirectTo = $_SESSION['redirect_to'] ?? '/ccz/';
                    unset($_SESSION['redirect_to']);
                    header("Location: $redirectTo");
                    exit;
                }

                $mensagem = "Verifique os dados informados";
            }
        }

        require_once "views/login.php";
    }

    public function logout()
    {
        $_SESSION = array();
        session_destroy();
        header("Location:/ccz/");
        exit;
    }
}
