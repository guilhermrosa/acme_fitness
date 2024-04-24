<?php

namespace DesafioBackend\dao;
use DesafioBackend\models\Produto;
use DesafioBackend\infrastructure\Conexao;
use \PDO;
use PDOException;

class ProdutoDao
{
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function adicionar(Produto $produto){

        try {
            $sql = "INSERT INTO produtos(codigo, nome, descricao, preco, peso, data_cadastro, id_categoria) VALUES(?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $produto->getCodigo());
            $stmt->bindValue(2, $produto->getNome());
            $stmt->bindValue(3, $produto->getDescricao());
            $stmt->bindValue(4, $produto->getPreco());
            $stmt->bindValue(5, $produto->getPeso());
            $stmt->bindValue(6, $produto->getDataCadastro());
            $stmt->bindValue(7, ($produto->getCategoria())->getId());
            $stmt->execute();
            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function alterar(Produto $produto){

        try {
            $sql = "UPDATE produtos SET codigo = ?, nome = ?, descricao = ?, preco = ?, peso = ?, data_cadastro = ?, id_categoria = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $produto->getCodigo());
            $stmt->bindValue(2, $produto->getNome());
            $stmt->bindValue(3, $produto->getDescricao());
            $stmt->bindValue(4, $produto->getPreco());
            $stmt->bindValue(5, $produto->getPeso());
            $stmt->bindValue(6, $produto->getDataCadastro());
            $stmt->bindValue(7, ($produto->getCategoria())->getId());
            $stmt->bindValue(8, $produto->getId());
            return $stmt->execute();

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function deletar($id){

        try {
            $sql = "DELETE FROM produtos WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function listarUm($id) {

        try {
            $stmt = $this->conexao->prepare("SELECT * FROM produtos WHERE id = ?");
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            $catDao = new CategoriaDao();
            $categoria = $catDao->listarUm($resultado['id_categoria']);
            $produto = new Produto($resultado['codigo'], $resultado['nome'], $resultado['descricao'], $resultado['preco'], $resultado['peso'], $resultado['data_cadastro'], $categoria);
            $produto->setId($resultado['id']);

            return $produto;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function listarTodos() {

        try {
            $stmt = $this->conexao->prepare("SELECT * FROM produtos");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $produtos = [];
            $catDao = new CategoriaDao();

            foreach ($resultados as $resultado){
                $categoria = $catDao->listarUm($resultado['id_categoria']);
                $produto = new Produto($resultado['codigo'], $resultado['nome'], $resultado['descricao'], $resultado['preco'], $resultado['peso'], $resultado['data_cadastro'], $categoria);
                $produto->setId($resultado['id']);
                $produtos[] = $produto;
            }

            return $produtos;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

}
