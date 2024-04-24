<?php

namespace DesafioBackend\models;

use JsonSerializable;

class Cliente implements JsonSerializable
{
    private $id;
    private $nome;
    private $cpf;
    private $dataNascimento;

    public function __construct($nome, $cpf, $dataNascimento) {
        $this->setNome($nome);
        $this->setCpf($cpf);
        $this->setDataNascimento($dataNascimento);
    }

	public function getId() {
		return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getNome() {
		return $this->nome;
	}

	public function setNome($value) {
		$this->nome = $value;
	}

	public function getCpf() {
		return $this->cpf;
	}

	public function setCpf($value) {
		$this->cpf = $value;
	}

	public function getDataNascimento() {
		return $this->dataNascimento;
	}

	public function setDataNascimento($value) {
		$this->dataNascimento = $value;
	}

	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'nome' => $this->nome,
			'cpf' => $this->cpf,
			'dataNascimento' => $this->dataNascimento
		];
	}
}