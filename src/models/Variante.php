<?php

namespace DesafioBackend\models;
use InvalidArgumentException;
use JsonSerializable;

class Variante implements JsonSerializable
{
    private $id;
    private $cor;
    private $imagem; //base64
    private $tamanho;
    private $quantidade;
	private $produto;

    public function __construct($cor, $imagem, $tamanho, $quantidade, Produto $produto) {
        $this->setCor($cor);
        $this->setImagem($imagem);
		$this->setTamanho($tamanho);
		$this->setQuantidade($quantidade);
		$this->setProduto($produto);
    }

	public function getId() {
		return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getCor() {
		return $this->cor;
	}

	public function setCor($value) {
		$this->cor = $value;
	}

	public function getImagem() {
		return $this->imagem;
	}

	public function setImagem($value) {
		if (base64_decode($value)) { //Verifica se a imagem já estava no formato base64
			$this->imagem = $value;
		} else {
			$extensoes = array("jpg", "jpeg", "png", "webp");
			$info = pathinfo($value["name"]);
			$extensao = strtolower($info["extension"]);
	
			if (in_array($extensao, $extensoes)) { //Verifica se o arquivo possui uma extensão suportada
				$this->imagem = base64_encode(file_get_contents($value["tmp_name"])); //Converte a imagem para o formato base64
			} else {
				throw new InvalidArgumentException("Extensão de arquivo não suportada.");
			}
		}
	}

    public function getTamanho() {
		return $this->tamanho;
	}

	public function setTamanho($value) {
		$this->tamanho = $value;
	}

	public function getQuantidade() {
		return $this->quantidade;
	}

	public function setQuantidade($value) {
		if($value >= 0){
			$this->quantidade = $value;
		}else{
			throw new InvalidArgumentException("Quantidade de estoque inválida.");
		}
	}

	public function getProduto() {
		return $this->produto;
	}

	public function setProduto(Produto $value) {
		$this->produto = $value;
	}

	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'cor' => $this->cor,
			'imagem' => $this->imagem,
			'tamanho' => $this->tamanho,
			'quantidade' => $this->quantidade,
			'produto' => $this->produto
		];
	}
}