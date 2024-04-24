<?php

namespace DesafioBackend\controllers;

use DesafioBackend\dao\ClienteDao;
use DesafioBackend\dao\EnderecoDao;
use DesafioBackend\models\Endereco;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class EnderecoController {
    private $endDao;
    private $cliDao;

    public function __construct(EnderecoDao $endDao, ClienteDao $cliDao) {
        $this->endDao = $endDao;
        $this->cliDao = $cliDao;
    }

    public function adicionar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
            
            $cliente = $this->cliDao->listarUm($data['id_cliente']);
            $endereco = new Endereco($cliente, $data['codigo'], $data['logradouro'], $data['numero'], $data['bairro'], $data['cidade'], $data['cep'], $data['complemento'] ?? '');
            $this->endDao->adicionar($endereco);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Endereço adicionado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao adicionar endereço: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function alterar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
            
            $cliente = $this->cliDao->listarUm($data['id_cliente']);
            $endereco = new Endereco($cliente, $data['codigo'], $data['logradouro'], $data['numero'], $data['bairro'], $data['cidade'], $data['cep'], $data['complemento'] ?? '');
            $endereco->setId($data['id']);
            $this->endDao->alterar($endereco);
            
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Endereço alterado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao alterar endereço: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deletar(Request $request, Response $response, array $args) {
        $id = $args['id'] ?? null;
        if ($id === null) {
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->getBody()->write(json_encode(['error' => 'ID do endereço não fornecido']));
        }

        try {
            $this->endDao->deletar($id);
            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Endereço deletado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao deletar endereço: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function listarTodos(Request $request, Response $response, array $args): Response {
        try {
            $enderecos = $this->endDao->listarTodos();

            if (empty($enderecos)) {
                $response->getBody()->write(json_encode(['error' => 'Nenhum endereço encontrado']));
                return $response->withStatus(404);
            }

            $response->getBody()->write(json_encode($enderecos));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Erro ao listar endereços: ' . $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function listarUm(Request $request, Response $response, array $args): Response {
        $id = $args['id'] ?? null;

        if ($id === null) {
            $response->getBody()->write(json_encode(['error' => 'ID do endereço não fornecido']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $endereco = $this->endDao->listarUm($id);

            if ($endereco === null) {
                $response->getBody()->write(json_encode(['error' => 'Endereço não encontrado']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode($endereco));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {

            $response->getBody()->write(json_encode(['error' => 'Erro ao buscar endereço: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
