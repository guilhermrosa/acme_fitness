<?php

namespace DesafioBackend\models;
use InvalidArgumentException;

class Pedido
{
    private $id;
    private $cliente; 
    private $endereco; 
	private $itensVenda = [];
    private $valorFrete = 10.00;
    private $desconto = 0;
    private $formaPagamento;
    private $dataPedido;

    public function __construct(Cliente $cliente, Endereco $endereco, $formaPagamento, $dataPedido) {
        $this->setCliente($cliente);
        $this->setEndereco($endereco);
        $this->setFormaPagamento($formaPagamento);
        $this->setDataPedido($dataPedido);
    }

	public function getId() {
		return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getCliente() {
		return $this->cliente;
	}

	public function setCliente(Cliente $value) {
		$this->cliente = $value;
	}

	public function getEndereco() {
		return $this->endereco;
	}

	public function setEndereco(Endereco $value) {
		$this->endereco = $value;
	}

	public function getValorFrete() {
		return $this->valorFrete;
	}

	public function setValorFrete($value) {
		$this->valorFrete = $value;
	}

	public function getDesconto() {
		return $this->desconto;
	}

	public function setDesconto($value) {
		$this->desconto = $value;
	}

	public function getFormaPagamento() {
		return $this->formaPagamento;
	}

	public function setFormaPagamento($value) {
		$formasPagamento = array('pix', 'cartao', 'boleto');
		$value = strtolower($value);

		if (in_array($value, $formasPagamento)) {
			
			$this->formaPagamento = $value;
			if($this->formaPagamento === 'pix'){
				$this->setDesconto(0.10);
			}

		}else{
			throw new InvalidArgumentException("Forma de pagamento inválida.");
		}
	}

	public function getDataPedido() {
		return $this->dataPedido;
	}

	public function setDataPedido($value) {
		$this->dataPedido = $value;
	}

	public function getItensVenda() {
		return $this->itensVenda;
	}

	public function addItemVenda(Variante $produto, $quantidade) {
		$this->itensVenda[] = ['produto' => $produto, 'quantidade' => $quantidade];
	}

	public function calcularValorTotal(){
		$total = $this->valorFrete;
		foreach ($this->itensVenda as $item){
			$total += ($item['produto']->getPreco()) * $item['quantidade'];
		}
		$total -= ($this->desconto * $total);
		return $total;
	}
}