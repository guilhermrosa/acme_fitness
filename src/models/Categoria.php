<?php

namespace DesafioBackend\models;

use JsonSerializable;

class Categoria implements JsonSerializable
{
    private $id;
    private $codigo;
    private $nome;
    private $descricao;

    public function __construct($codigo, $nome, $descricao) {
        $this->setCodigo($codigo);
        $this->setNome($nome);
        $this->setDescricao($descricao);
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nome' => $this->nome,
            'descricao' => $this->descricao
        ];
    }
}