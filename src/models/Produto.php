<?php

namespace DesafioBackend\models;

use JsonSerializable;

class Produto implements JsonSerializable
{
    private $id;
    private $codigo;
    private $nome;
    private $descricao;
    private $preco;
    private $peso;
    private $dataCadastro;
    private $categoria;

    public function __construct($codigo, $nome, $descricao, $preco, $peso, $dataCadastro, Categoria $categoria) {
        $this->setCodigo($codigo);
        $this->setNome($nome);
        $this->setDescricao($descricao);
        $this->setPreco($preco);
        $this->setPeso($peso);
        $this->setDataCadastro($dataCadastro);
        $this->setCategoria($categoria);
    }

	public function getId() {
		return $this->id;
	}

	public function setId($valor) {
		$this->id = $valor;
	}

	public function getCodigo() {
		return $this->codigo;
	}

	public function setCodigo($valor) {
		$this->codigo = $valor;
	}

	public function getNome() {
		return $this->nome;
	}

	public function setNome($valor) {
		$this->nome = $valor;
	}

	public function getDescricao() {
		return $this->descricao;
	}

	public function setDescricao($valor) {
		$this->descricao = $valor;
	}

	public function getPreco() {
		return $this->preco;
	}

	public function setPreco($valor) {
		$this->preco = $valor;
	}

	public function getPeso() {
		return $this->peso;
	}

	public function setPeso($valor) {
		$this->peso = $valor;
	}

	public function getDataCadastro() {
		return $this->dataCadastro;
	}

	public function setDataCadastro($valor) {
		$this->dataCadastro = $valor;
	}

	public function getCategoria() {
		return $this->categoria;
	}

	public function setCategoria(Categoria $valor) {
		$this->categoria = $valor;
	}

	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'codigo' => $this->codigo,
			'nome' => $this->nome,
			'descricao' => $this->descricao,
			'preco' => $this->preco,
			'peso' => $this->peso,
			'dataCadastro' => $this->dataCadastro,
			'categoria' => $this->categoria
		];
	}
}