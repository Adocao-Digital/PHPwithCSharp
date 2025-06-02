<?php

class UsuarioDAO extends ConexaoMongo
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
    }

    public function getDb()
    {
        return $this->db;
    }

    // Verifica se email ou cpf já existem (para cadastro)
    public function login(Usuario $usuario)
    {
        $filtros = [];

        if (!empty($usuario->Email)) {
            $filtros['Email'] = $usuario->Email;
        }

        if (!empty($usuario->Cpf)) {
            $filtros['Cpf'] = $usuario->Cpf;
        }

        if (empty($filtros)) {
            return false;
        }

        // Se busca pelo email, primeiro busca por email
        if (isset($filtros['Email']) && !isset($filtros['Cpf'])) {
            $user = $this->db->usuarios->findOne(['Email' => $filtros['Email']]);
            return $user ? $user : false;
        }

        // Se busca pelo cpf, primeiro busca por cpf
        if (isset($filtros['Cpf']) && !isset($filtros['Email'])) {
            $user = $this->db->usuarios->findOne(['Cpf' => $filtros['Cpf']]);
            return $user ? $user : false;
        }

        // Se vier os dois, verifica se algum existe
        $user = $this->db->usuarios->findOne([
            '$or' => [
                ['Email' => $filtros['Email']],
                ['Cpf' => $filtros['Cpf']]
            ]
        ]);

        return $user ? $user : false;
    }

    public function cadastro(Usuario $usuario)
    {
        // Verifica duplicidade email e cpf
        $exists = $this->db->usuarios->findOne([
            '$or' => [
                ['Email' => $usuario->Email],
                ['Cpf' => $usuario->Cpf]
            ]
        ]);

        if ($exists) {
            return "E-mail ou CPF já cadastrado";
        }

        $doc = [
            'Nome' => $usuario->Nome,
            'Email' => $usuario->Email,
            'Cpf' => $usuario->Cpf,
            'Senha' => $usuario->Senha,
            'Telefone' => $usuario->Telefone,
            'Foto' => $usuario->Foto,
            'Ativo' => $usuario->Ativo,
            'DataNascimento' => new MongoDB\BSON\UTCDateTime($usuario->DataNascimento->getTimestamp() * 1000),
            'Endereco' => [
                'Rua' => $usuario->Endereco->Rua,
                'Numero' => $usuario->Endereco->Numero,
                'Bairro' => $usuario->Endereco->Bairro,
                'Cidade' => $usuario->Endereco->Cidade,
                'Estado' => $usuario->Endereco->Estado,
                'Cep' => $usuario->Endereco->Cep,
            ],
            'Role' => 'USER' // Default role ao cadastrar
        ];

        $result = $this->db->usuarios->insertOne($doc);

        if ($result->getInsertedCount() === 1) {
            return "Cadastro realizado com sucesso!";
        } else {
            return "Erro ao cadastrar usuário.";
        }
    }
}
