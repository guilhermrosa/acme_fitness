<?php

namespace DesafioBackend\controllers;
use DesafioBackend\dao\CategoriaDao;
use DesafioBackend\models\Categoria;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class CategoriaController {
    private $catDao;

    public function __construct(CategoriaDao $catDao) {
        $this->catDao = $catDao;
    }

    public function adicionar(Request $request, Response $response, array $args) {

        try {
            $data = $request->getParsedBody();
        
            $categoria = new Categoria($data['codigo'], $data['nome'], $data['descricao']);
            $this->catDao->adicionar($categoria);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Categoria adicionada com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao adicionar categoria: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function alterar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
        
            $categoria = new Categoria($data['codigo'], $data['nome'], $data['descricao']);
            $categoria->setId($data['id']);
            $this->catDao->alterar($categoria);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Categoria alterada com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao alterar categoria: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deletar(Request $request, Response $response, array $args){
        $id = $args['id'] ?? null;
        if ($id === null) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->getBody()->write(json_encode(['error' => 'ID da categoria não fornecido']));
        }

        try {
            $this->catDao->deletar($id);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Categoria deletada com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao deletar categoria: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function listarTodos(Request $request, Response $response, array $args): Response {
        try {
            $categorias = $this->catDao->listarTodos();
            if (empty($categorias)) {
                $response->getBody()->write(json_encode(['error' => 'Nenhuma categoria encontrada']));
                return $response->withStatus(404);
            }
            $response->getBody()->write(json_encode($categorias));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao listar categorias: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function listarUm(Request $request, Response $response, array $args): Response {
        $id = $args['id'] ?? null;

        if ($id === null) {
            $response->getBody()->write(json_encode(['error' => 'ID da categoria não fornecido']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $categoria = $this->catDao->listarUm($id);

            if ($categoria === null) {
                $response->getBody()->write(json_encode(['error' => 'Categoria não encontrada']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode($categoria));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {

            $response->getBody()->write(json_encode(['error' => 'Erro ao buscar categoria: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}