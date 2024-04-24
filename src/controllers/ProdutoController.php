<?php

namespace DesafioBackend\controllers;

use DesafioBackend\dao\CategoriaDao;
use DesafioBackend\dao\ProdutoDao;
use DesafioBackend\models\Produto;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class ProdutoController {
    private $proDao;
    private $catDao;

    public function __construct(ProdutoDao $proDao, CategoriaDao $catDao) {
        $this->proDao = $proDao;
        $this->catDao = $catDao;
    }

    public function adicionar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
            
            $categoria = $this->catDao->listarUm($data['id_categoria']);
            $dataCadastro = date('Y-m-d');

            $produto = new Produto($data['codigo'], $data['nome'], $data['descricao'], $data['preco'], $data['peso'], $dataCadastro, $categoria);
            $this->proDao->adicionar($produto);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Produto adicionado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao adicionar produto: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function alterar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
        
            $categoria = $this->catDao->listarUm($data['id_categoria']);
            $dataCadastro = date('Y-m-d');

            $produto = new Produto($data['codigo'], $data['nome'], $data['descricao'], $data['preco'], $data['peso'], $dataCadastro, $categoria);
            $produto->setId($data['id']);
            $this->proDao->alterar($produto);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Produto alterado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao alterar produto: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
    
    public function deletar(Request $request, Response $response, array $args) {
        $id = $args['id'] ?? null;
        if ($id === null) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->getBody()->write(json_encode(['error' => 'ID do produto não fornecido']));
        }

        try {
            $this->proDao->deletar($id);
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Produto deletado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao deletar produto: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function listarTodos(Request $request, Response $response, array $args): Response {
        try {
            $produtos = $this->proDao->listarTodos();

            if (empty($produtos)) {
                $response->getBody()->write(json_encode(['error' => 'Nenhum produto encontrado']));
                return $response->withStatus(404);
            }

            $response->getBody()->write(json_encode($produtos));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao listar produtos: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function listarUm(Request $request, Response $response, array $args): Response {
        $id = $args['id'] ?? null;

        if ($id === null) {
            $response->getBody()->write(json_encode(['error' => 'ID do produto não fornecido']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $produto = $this->proDao->listarUm($id);

            if ($produto === null) {
                $response->getBody()->write(json_encode(['error' => 'Produto não encontrado']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode($produto));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {

            $response->getBody()->write(json_encode(['error' => 'Erro ao buscar produto: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
