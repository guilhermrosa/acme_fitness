<?php

namespace DesafioBackend\controllers;

use DesafioBackend\dao\ClienteDao;
use DesafioBackend\dao\enderecoDao;
use DesafioBackend\dao\PedidoDao;
use DesafioBackend\dao\VarianteDao;
use DesafioBackend\models\Pedido;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class PedidoController {
    private $pedDao;
    private $cliDao;
    private $endDao;
    private $varDao;

    public function __construct(PedidoDao $pedDao, ClienteDao $cliDao, EnderecoDao $endDao, VarianteDao $varDao) {
        $this->pedDao = $pedDao;
        $this->cliDao = $cliDao;
        $this->endDao = $endDao;
        $this->varDao = $varDao;
    }

    public function adicionar(Request $request, Response $response, array $args) {
        try {
            $data = $request->getParsedBody();
            var_dump($data);

            // Obtendo os dados do cliente
            $cliente = $this->cliDao->listarUm($data['id_cliente']);

            // Obtendo os dados do endereço
            $endereco = $this->endDao->listarUm($data['id_endereco']);

            $formaPagamento = $data['formaPagamento'];
            $dataPedido = date('Y-m-d');

            $pedido = new Pedido($cliente, $endereco, $formaPagamento, $dataPedido);

            // Obtendo os itens de venda
            $itensData = $data['itensPedido'];
            
            foreach ($itensData as $item) {
                $variante = $this->varDao->listarUm($item['id_variante']);
                $pedido->addItemVenda($variante, $item['quantidade']);
            }

            $this->pedDao->adicionar($pedido);

            $response = $response->withHeader('Content-Type', 'application/json');
            $jsonResponse = json_encode(['message' => 'Pedido adicionado com sucesso']);
            $response->getBody()->write($jsonResponse);
            return $response;
        } catch (Exception $e) {
            // Resposta de erro
            $response->getBody()->write(json_encode(['error' => 'Erro ao adicionar pedido: ' . $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

}

