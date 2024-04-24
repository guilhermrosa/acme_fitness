<?php
namespace DesafioBackend\dao;
use DesafioBackend\models\Variante;
use DesafioBackend\infrastructure\Conexao;
use \PDO;
use PDOException;

class VarianteDao
{
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function adicionar(Variante $variante){

        try {
            $sql = "INSERT INTO variantes(id_produto, cor, imagem, tamanho, estoque) VALUES(?, ?, ?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $variante->getProduto()->getId());
            $stmt->bindValue(2, $variante->getCor());
            $stmt->bindValue(3, $variante->getImagem());
            $stmt->bindValue(4, $variante->getTamanho());
            $stmt->bindValue(5, $variante->getQuantidade());
            $stmt->execute();
            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function alterar(Variante $variante){

        try {
            $sql = "UPDATE variantes SET  id_produto = ?, cor = ?, imagem = ?, tamanho = ?, estoque = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $variante->getProduto()->getId());
            $stmt->bindValue(2, $variante->getCor());
            $stmt->bindValue(3, $variante->getImagem());
            $stmt->bindValue(4, $variante->getTamanho());
            $stmt->bindValue(5, $variante->getQuantidade());
            $stmt->bindValue(6, $variante->getId());
            $stmt->execute();
            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function deletar($id){

        try {
            $sql = "DELETE FROM variantes WHERE id = ?";
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
            $stmt = $this->conexao->prepare("SELECT * FROM variantes WHERE id = ?");
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            $proDao = new ProdutoDao();
            $produto = $proDao->listarUm($resultado['id_produto']);
            $variante = new Variante($resultado['cor'], $resultado['imagem'], $resultado['tamanho'], $resultado['estoque'], $produto);
            $variante->setId($resultado['id']);

            return $variante;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function listarTodos() {

        try {
            $stmt = $this->conexao->prepare("SELECT * FROM variantes");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $variantes = [];
            $proDao = new ProdutoDao();

            foreach ($resultados as $resultado){
                $produto = $proDao->listarUm($resultado['id_produto']);
                $variante = new Variante($resultado['cor'], $resultado['imagem'], $resultado['tamanho'], $resultado['estoque'], $produto);
                $variante->setId($resultado['id']);
                $variantes[] = $variante;
            }

            return $variantes;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }
}
