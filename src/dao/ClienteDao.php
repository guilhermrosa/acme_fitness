<?php

namespace DesafioBackend\dao;
use DesafioBackend\models\Cliente;
use DesafioBackend\infrastructure\Conexao;
use \PDO;
use PDOException;

class ClienteDao
{
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function adicionar(Cliente $cliente){

        try {
            $sql = "INSERT INTO clientes(nome, cpf, data_nascimento) VALUES(?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $cliente->getNome());
            $stmt->bindValue(2, $cliente->getCpf());
            $stmt->bindValue(3, $cliente->getDataNascimento());
            $stmt->execute();
            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function alterar(Cliente $cliente){

        try {
            $sql = "UPDATE clientes SET nome = ?, cpf = ?, data_nascimento = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $cliente->getNome());
            $stmt->bindValue(2, $cliente->getCpf());
            $stmt->bindValue(3, $cliente->getDataNascimento());
            $stmt->bindValue(4, $cliente->getId());
            $stmt->execute();            
            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function deletar($id){

        try {
            $sql = "DELETE FROM clientes WHERE id = ?";
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
            $stmt = $this->conexao->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            $cliente = new Cliente($resultado['nome'], $resultado['cpf'], $resultado['data_nascimento']);
            $cliente->setId($resultado['id']);

            return $cliente;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function listarTodos() {

        try {
            $stmt = $this->conexao->prepare("SELECT * FROM clientes");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $clientes = [];

            foreach ($resultados as $resultado){
                $cliente = new Cliente($resultado['nome'], $resultado['cpf'], $resultado['data_nascimento']);
                $cliente->setId($resultado['id']);
                $clientes[] = $cliente;
            }

            return $clientes;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

}
