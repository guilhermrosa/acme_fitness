<?php

namespace DesafioBackend\dao;
use DesafioBackend\models\Categoria;
use DesafioBackend\infrastructure\Conexao;
use \PDO;
use PDOException;

class CategoriaDao
{   
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }
    
    

    public function adicionar(Categoria $categoria){
        try {
            $sql = "INSERT INTO categorias(codigo, nome, descricao) VALUES(?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $categoria->getCodigo());
            $stmt->bindValue(2, $categoria->getNome());
            $stmt->bindValue(3, $categoria->getDescricao());
            $stmt->execute();

            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function alterar(Categoria $categoria){
        

        try {
            $sql = "UPDATE categorias SET codigo = ?, nome = ?, descricao = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $categoria->getCodigo());
            $stmt->bindValue(2, $categoria->getNome());
            $stmt->bindValue(3, $categoria->getDescricao());
            $stmt->bindValue(4, $categoria->getId());
            $stmt->execute();

            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function deletar($id){

        try {
            $sql = "DELETE FROM categorias WHERE id = ?";
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
            $stmt = $this->conexao->prepare("SELECT * FROM categorias WHERE id = ?");
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            $categoria = new Categoria($resultado['codigo'], $resultado['nome'], $resultado['descricao']);
            $categoria->setId($resultado['id']);

            return $categoria;
    
        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function listarTodos() {
        

        try {
            $stmt = $this->conexao->prepare("SELECT * FROM categorias");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $categorias = [];

            foreach ($resultados as $resultado){
                $categoria = new Categoria($resultado['codigo'], $resultado['nome'], $resultado['descricao']);
                $categoria->setId($resultado['id']);
                $categorias[] = $categoria;
            }
            return $categorias;  

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

}
