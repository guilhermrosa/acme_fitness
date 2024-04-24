<?php

namespace DesafioBackend\dao;
use DesafioBackend\models\Endereco;
use DesafioBackend\infrastructure\Conexao;
use \PDO;
use PDOException;

class enderecoDao
{
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function adicionar(Endereco $endereco){

        try {
            $sql = "INSERT INTO enderecos(codigo, logradouro, numero, bairro, cidade, cep, complemento, id_cliente) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $endereco->getCodigo());
            $stmt->bindValue(2, $endereco->getLogradouro());
            $stmt->bindValue(3, $endereco->getNumero());
            $stmt->bindValue(4, $endereco->getBairro());
            $stmt->bindValue(5, $endereco->getCidade());
            $stmt->bindValue(6, $endereco->getCep());
            $stmt->bindValue(7, $endereco->getComplemento());
            $stmt->bindValue(8, ($endereco->getcliente())->getId());
            $stmt->execute();
            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function alterar(Endereco $endereco){

        try {
            $sql = "UPDATE enderecos SET codigo = ?, logradouro = ?, numero = ?, bairro = ?, cidade = ?, cep = ?, complemento = ?, id_cliente = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $endereco->getCodigo());
            $stmt->bindValue(2, $endereco->getLogradouro());
            $stmt->bindValue(3, $endereco->getNumero());
            $stmt->bindValue(4, $endereco->getBairro());
            $stmt->bindValue(5, $endereco->getCidade());
            $stmt->bindValue(6, $endereco->getCep());
            $stmt->bindValue(7, $endereco->getComplemento());
            $stmt->bindValue(8, ($endereco->getcliente())->getId());
            $stmt->bindValue(9, $endereco->getId());
            $stmt->execute();
            return true;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function deletar($id){

        try {
            $sql = "DELETE FROM enderecos WHERE id = ?";
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
            $stmt = $this->conexao->prepare("SELECT * FROM enderecos WHERE id = ?");
            $stmt->bindValue(1, $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            $cliDao = new ClienteDao();
            $cliente = $cliDao->listarUm($resultado['id_cliente']);
            $endereco = new Endereco( $cliente, $resultado['codigo'], $resultado['logradouro'], $resultado['numero'], $resultado['bairro'], $resultado['cidade'], $resultado['cep'], $resultado['complemento']);
            $endereco->setId($resultado['id']);

            return $endereco;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

    public function listarTodos() {

        try {
            $stmt = $this->conexao->prepare("SELECT * FROM enderecos");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $enderecos = [];
            $cliDao = new ClienteDao();

            foreach ($resultados as $resultado){
                $cliente = $cliDao->listarUm($resultado['id_cliente']);
                $endereco = new Endereco( $cliente, $resultado['codigo'], $resultado['logradouro'], $resultado['numero'], $resultado['bairro'], $resultado['cidade'], $resultado['cep'], $resultado['complemento']);
                $endereco->setId($resultado['id']);
                $enderecos[] = $endereco;
            }

            return $enderecos;

        } catch (PDOException $e) {

            echo "Erro: " . $e->getMessage();
            return false;
        }
    }

}