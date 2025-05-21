<?php
class UsuarioDAO extends ConexaoMongo {
    public function __construct() {
        parent::__construct();
    }

    // Método para inserir um novo usuário no MongoDB
    public function inserir(Usuario $usuario) {
        try {
            $collection = $this->db->Usuarios;

            // Cria o documento a ser inserido
            $document = [
                'nome' => $usuario->nome,
                'email' => $usuario->email,
                'cpf' => $usuario->cpf,
                'senha' => $usuario->senha,
                'telefone' => $usuario->telefone,
                'data_nascimento' => $usuario->dataNascimento->format('Y-m-d'),
                'foto' => $usuario->foto,
                'ativo' => $usuario->ativo,
                'endereco' => [
                    'rua' => $usuario->endereco->rua,
                    'numero' => $usuario->endereco->numero,
                    'bairro' => $usuario->endereco->bairro,
                    'cidade' => $usuario->endereco->cidade,
                    'estado' => $usuario->endereco->estado,
                    'cep' => $usuario->endereco->cep
                ]
            ];

            $result = $collection->insertOne($document);
            return "Usuário cadastrado com sucesso! ID: " . $result->getInsertedId();
        } catch (Exception $e) {
            echo "Erro ({$e->getCode()}): " . $e->getMessage();
            die();
        }
    }

    // Método para fazer o login do usuário
    public function login(Usuario $usuario) {
        try {
            $collection = $this->db->Usuarios;

            // Busca o usuário pelo e-mail
            $user = $collection->findOne(['email' => $usuario->email]);

            if ($user && password_verify($usuario->senha, $user['senha'])) {
                return $user; // Login válido
            } else {
                return null; // Email não encontrado ou senha inválida
            }
        } catch (Exception $e) {
            echo "Erro ({$e->getCode()}): " . $e->getMessage();
            die();
        }
    }

    public function cadastro(Usuario $usuario) {
        try {
            $collection = $this->db->Usuarios;

            // Verifica se já existe um usuário com o mesmo email ou CPF
            $usuarioExistente = $collection->findOne([
                '$or' => [
                    ['email' => $usuario->email],
                    ['cpf' => $usuario->cpf]
                ]
            ]);

            if ($usuarioExistente) {
                return "Já existe um usuário com esse e-mail ou CPF.";
            }

            // Prepara o documento para inserção
            $document = [
                'nome' => $usuario->nome,
                'email' => $usuario->email,
                'cpf' => $usuario->cpf,
                'senha' => password_hash($usuario->senha, PASSWORD_DEFAULT),
                'telefone' => $usuario->telefone,
                'data_nascimento' => $usuario->dataNascimento->format('Y-m-d'),
                'foto' => $usuario->foto,
                'ativo' => $usuario->ativo,
                'endereco' => [
                    'rua' => $usuario->endereco->rua,
                    'numero' => $usuario->endereco->numero,
                    'bairro' => $usuario->endereco->bairro,
                    'cidade' => $usuario->endereco->cidade,
                    'estado' => $usuario->endereco->estado,
                    'cep' => $usuario->endereco->cep
                ]
            ];

            $result = $collection->insertOne($document);

            return "Usuário cadastrado com sucesso! ID: " . $result->getInsertedId();
        } catch (Exception $e) {
            return "Erro ({$e->getCode()}): " . $e->getMessage();
        }
    }

}
?>
