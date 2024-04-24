<?php

namespace DesafioBackend\dao;
use DesafioBackend\models\Pedido;
use DesafioBackend\infrastructure\Conexao;
use PDOException;

class PedidoDao
{   
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function adicionar(Pedido $pedido){
        try {
            $this->conexao->beginTransaction();

            $sql = "INSERT INTO pedidos (id_cliente, id_endereco, valor_frete, desconto, valor_total, forma_pagamento, data_pedido) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $pedido->getCliente()->getId());
            $stmt->bindValue(2, $pedido->getEndereco()->getId());
            $stmt->bindValue(3, $pedido->getValorFrete());
            $stmt->bindValue(4, $pedido->getDesconto());
            $stmt->bindValue(5, $pedido->calcularValorTotal());
            $stmt->bindValue(6, $pedido->getFormaPagamento());
            $stmt->bindValue(7, $pedido->getDataPedido());
            $stmt->execute();

            $idPedido = $this->conexao->lastInsertId();

            $this->adicionarItensPedido($pedido->getItensVenda(), $idPedido);

            $this->conexao->commit();
            return true;

        } catch (PDOException $e) {
            $this->conexao->rollBack();
            echo "Erro ao adicionar pedido: " . $e->getMessage();
            return false;
        }
    }

    private function adicionarItensPedido($itens, $id) {
        $varDao = new VarianteDao();
    
        foreach ($itens as $item) {
            $quantidadeDisponivel = $item['produto']->getQuantidade();
    
            if ($quantidadeDisponivel >= $item['quantidade']) {
                $novaQuantidade = $quantidadeDisponivel - $item['quantidade'];
                $item['produto']->setQuantidade($novaQuantidade);
                $varDao->alterar($item['produto']);
    
                $sql = "INSERT INTO item_pedido(id_pedido, id_variante, preco, quantidade) VALUES(?, ?, ?, ?)";
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindValue(1, $id);
                $stmt->bindValue(2, $item['produto']->getId());
                $stmt->bindValue(3, $item['produto']->getPreco());
                $stmt->bindValue(4, $item['quantidade']);
                $stmt->execute();
            } else {
                throw new PDOException("Quantidade insuficiente em estoque para a variante de id = {$item['produto']->getId()}");
            }
        }
    }
    
}
