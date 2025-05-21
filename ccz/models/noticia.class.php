<?php

use MongoDB\BSON\Uuid;
use DateTime;

class Noticia {
    public Uuid $_id;
    public string $titulo;
    public string $conteudo;
    public string $foto;
    public DateTime $dataPublicacao;
    public ?string $idAutor;
    public ?string $nomeAutor;
    public ?DateTime $dataEdicao;
    public ?string $idEditor;
    public ?string $nomeEditor;

    public function __construct(
        string $titulo,
        string $conteudo,
        string $foto,
        ?string $idAutor = null,
        ?string $nomeAutor = null,
        ?DateTime $dataEdicao = null,
        ?string $idEditor = null,
        ?string $nomeEditor = null
    ) {
        $this->_id = Uuid::fromString(uuid_create(UUID_TYPE_RANDOM)); // precisa da ext `uuid`
        $this->titulo = $titulo;
        $this->conteudo = $conteudo;
        $this->foto = $foto;
        $this->dataPublicacao = new DateTime(); // agora
        $this->idAutor = $idAutor;
        $this->nomeAutor = $nomeAutor;
        $this->dataEdicao = $dataEdicao;
        $this->idEditor = $idEditor;
        $this->nomeEditor = $nomeEditor;
    }

    public static function fromMongo(array $doc): Noticia {
        $n = new Noticia(
            $doc['Titulo'] ?? '',
            $doc['Conteudo'] ?? '',
            $doc['Foto'] ?? '',
            $doc['IdAutor'] ?? null,
            $doc['NomeAutor'] ?? null,
            isset($doc['DataEdicao']) ? $doc['DataEdicao']->toDateTime() : null,
            $doc['IdEditor'] ?? null,
            $doc['NomeEditor'] ?? null
        );
        $n->_id = $doc['_id'];
        $n->dataPublicacao = $doc['DataPublicacao']->toDateTime();
        return $n;
    }
}

?>
