<?php

namespace DesafioBackend\controllers;
use DesafioBackend\dao\ClienteDao;
use DesafioBackend\models\Cliente;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class ClienteController {
    private $cliDao;

    public function __construct(ClienteDao $cliDao) {
        $this->cliDao = $cliDao;
    }

    public function adicionar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
        
            $cliente = new Cliente($data['nome'], $data['cpf'], $data['dataNascimento']);
            $this->cliDao->adicionar($cliente);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Cliente adicionado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao adicionar cliente: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function alterar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
        
            $cliente = new Cliente($data['nome'], $data['cpf'], $data['dataNascimento']);
            $cliente->setId($data['id']);
            $this->cliDao->alterar($cliente);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Cliente alterado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao alterar cliente: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deletar(Request $request, Response $response, array $args) {
        $id = $args['id'] ?? null;
        if ($id === null) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->getBody()->write(json_encode(['error' => 'ID do cliente não fornecido']));
        }

        try {
            $this->cliDao->deletar($id);
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Cliente deletado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao deletar cliente: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function listarTodos(Request $request, Response $response, array $args): Response {
        try {
            $clientes = $this->cliDao->listarTodos();

            if (empty($clientes)) {
                $response->getBody()->write(json_encode(['error' => 'Nenhum cliente encontrado']));
                return $response->withStatus(404);
            }

            $response->getBody()->write(json_encode($clientes));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao listar clientes: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function listarUm(Request $request, Response $response, array $args): Response {
        $id = $args['id'] ?? null;

        if ($id === null) {
            $response->getBody()->write(json_encode(['error' => 'ID do cliente não fornecido']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $cliente = $this->cliDao->listarUm($id);

            if ($cliente === null) {
                $response->getBody()->write(json_encode(['error' => 'Cliente não encontrado']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode($cliente));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {

            $response->getBody()->write(json_encode(['error' => 'Erro ao buscar cliente: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}

?>