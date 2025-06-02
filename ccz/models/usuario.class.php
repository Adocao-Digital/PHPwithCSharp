<?php

class Endereco {
    public string $rua;
    public string $numero;
    public string $bairro;
    public string $cidade;
    public string $estado;
    public string $cep;

    public function __construct($rua = '', $numero = '', $bairro = '', $cidade = '', $estado = '', $cep = '') {
        $this->rua = $rua;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->cep = $cep;
    }
}

class Usuario {
    public string $nome;
    public string $email;
    public string $cpf;
    public string $senha;
    public Endereco $endereco;
    public string $telefone;
    public DateTime $dataNascimento;
    public ?string $foto;
    public bool $ativo;

    public function __construct(
        string $nome = '',
        string $email = '',
        string $cpf = '',
        string $senha = '',
        ?Endereco $endereco = null,
        string $telefone = '',
        ?DateTime $dataNascimento = null,
        ?string $foto = null,
        bool $ativo = true
    ) {
        $this->nome = $nome;
        $this->email = $email;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->endereco = $endereco ?? new Endereco();
        $this->telefone = $telefone;
        $this->dataNascimento = $dataNascimento ?? new DateTime();
        $this->foto = $foto;
        $this->ativo = $ativo;
    }

    public function toArray(): array {
        return [
            'Nome' => $this->nome,
            'Email' => $this->email,
            'Cpf' => $this->cpf,
            'Senha' => $this->senha,
            'Telefone' => $this->telefone,
            'Foto' => $this->foto,
            'Ativo' => $this->ativo,
            'DataNascimento' => new MongoDB\BSON\UTCDateTime($this->dataNascimento->getTimestamp() * 1000),
            'Endereco' => [
                'Rua' => $this->endereco->rua,
                'Numero' => $this->endereco->numero,
                'Bairro' => $this->endereco->bairro,
                'Cidade' => $this->endereco->cidade,
                'Estado' => $this->endereco->estado,
                'Cep' => $this->endereco->cep,
            ],
            'Role' => 'USER'
        ];
    }
}
?>
