<?php

namespace DesafioBackend\controllers;

use DesafioBackend\dao\ProdutoDao;
use DesafioBackend\dao\VarianteDao;
use DesafioBackend\models\Variante;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class VarianteController {
    private $varDao;
    private $proDao;

    public function __construct(VarianteDao $varDao, ProdutoDao $proDao) {
        $this->varDao = $varDao;
        $this->proDao = $proDao;
    }

    public function adicionar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
            
            $produto = $this->proDao->listarUm($data['id_produto']);
            $variante = new Variante($data['cor'], $data['imagem'], $data['tamanho'], $data['quantidade'], $produto);
            $this->varDao->adicionar($variante);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Variante adicionada com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao adicionar variante: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function alterar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
            
            $produto = $this->proDao->listarUm($data['id_produto']);
            $variante = new Variante($data['cor'], $data['imagem'], $data['tamanho'], $data['quantidade'], $produto);
            $variante->setId($data['id']);
            $this->varDao->alterar($variante);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Variante alterada com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao alterar variante: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deletar(Request $request, Response $response, array $args) {
        $id = $args['id'] ?? null;
        if ($id === null) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->getBody()->write(json_encode(['error' => 'ID da variante não fornecido']));
        }

        try {
            $this->varDao->deletar($id);
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Variante deletada com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao deletar variante: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function listarTodos(Request $request, Response $response, array $args): Response {
        try {
            $variantes = $this->varDao->listarTodos();

            if (empty($variantes)) {
                $response->getBody()->write(json_encode(['error' => 'Nenhuma variante encontrada']));
                return $response->withStatus(404);
            }

            $response->getBody()->write(json_encode($variantes));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao listar variantes: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function listarUm(Request $request, Response $response, array $args): Response {
        $id = $args['id'] ?? null;

        if ($id === null) {
            $response->getBody()->write(json_encode(['error' => 'ID da variante não fornecido']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $variante = $this->varDao->listarUm($id);

            if ($variante === null) {
                $response->getBody()->write(json_encode(['error' => 'Variante não encontrada']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode($variante));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {

            $response->getBody()->write(json_encode(['error' => 'Erro ao buscar variante: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
