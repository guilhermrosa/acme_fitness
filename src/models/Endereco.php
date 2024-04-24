<?php

namespace DesafioBackend\models;

use JsonSerializable;

class Endereco implements JsonSerializable
{
    private $id;
    private $codigo;
    private $logradouro;
    private $numero;
    private $bairro;
    private $cidade;
    private $cep;
    private $complemento;
    private $cliente;

    public function __construct(Cliente $cliente, $codigo, $logradouro, $numero, $bairro, $cidade, $cep, $complemento = "") {
        $this->setCodigo($codigo);
        $this->setLogradouro($logradouro);
        $this->setNumero($numero);
        $this->setBairro($bairro);
        $this->setCidade($cidade);
        $this->setCep($cep);
        $this->setComplemento($complemento);
        $this->setCliente($cliente);
    }

	public function getId() {
		return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getCodigo() {
		return $this->codigo;
	}

	public function setCodigo($value) {
		$this->codigo = $value;
	}

	public function getLogradouro() {
		return $this->logradouro;
	}

	public function setLogradouro($value) {
		$this->logradouro = $value;
	}

	public function getNumero() {
		return $this->numero;
	}

	public function setNumero($value) {
		$this->numero = $value;
	}

	public function getBairro() {
		return $this->bairro;
	}

	public function setBairro($value) {
		$this->bairro = $value;
	}

	public function getCidade() {
		return $this->cidade;
	}

	public function setCidade($value) {
		$this->cidade = $value;
	}

	public function getCep() {
		return $this->cep;
	}

	public function setCep($value) {
		$this->cep = $value;
	}

	public function getComplemento() {
		return $this->complemento;
	}

	public function setComplemento($value) {
		$this->complemento = $value;
	}

	public function getCliente() {
		return $this->cliente;
	}

	public function setCliente(Cliente $value) {
		$this->cliente = $value;
	}
	
	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'codigo' => $this->codigo,
			'logradouro' => $this->logradouro,
			'numero' => $this->numero,
			'bairro' => $this->bairro,
			'cidade' => $this->cidade,
			'cep' => $this->cep,
			'complemento' => $this->complemento,
			'cliente' => $this->cliente
		];
	}
	
}