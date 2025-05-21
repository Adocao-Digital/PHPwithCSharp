<?php

class Endereco {
    // Defina os campos de endereço conforme necessário.
    public string $rua;
    public string $numero;
    public string $bairro;
    public string $cidade;
    public string $estado;
    public string $cep;

    public function __construct($rua, $numero, $bairro, $cidade, $estado, $cep) {
        $this->rua = $rua;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->cep = $cep;
    }
}

class Usuario {
    /**
     * @var string Nome Completo - obrigatório
     */
    public string $nome;

    /**
     * @var string E-mail - obrigatório, formato de e-mail
     */
    public string $email;

    /**
     * @var string CPF - obrigatório, exatamente 11 números
     */
    public string $cpf;

    /**
     * @var string Senha - obrigatório, mínimo 6 caracteres
     */
    public string $senha;

    /**
     * @var Endereco Endereço - obrigatório
     */
    public Endereco $endereco;

    /**
     * @var string Telefone - obrigatório, formato de telefone
     */
    public string $telefone;

    /**
     * @var DateTime Data de Nascimento - obrigatório
     */
    public DateTime $dataNascimento;

    /**
     * @var string|null Caminho da Foto
     */
    public ?string $foto;

    /**
     * @var bool Ativo - obrigatório
     */
    public bool $ativo;

    public function __construct(
        string $nome,
        string $email,
        string $cpf,
        string $senha,
        Endereco $endereco,
        string $telefone,
        DateTime $dataNascimento,
        ?string $foto,
        bool $ativo
    ) {
        $this->nome = $nome;
        $this->email = $email;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->endereco = $endereco;
        $this->telefone = $telefone;
        $this->dataNascimento = $dataNascimento;
        $this->foto = $foto;
        $this->ativo = $ativo;
    }
}