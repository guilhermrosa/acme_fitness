<?php

use Slim\App;

return function (App $app) {
    $app->group('/api', function () use ($app) {

        $app->post('/categoria/adicionar', '\DesafioBackend\controllers\CategoriaController:adicionar');
        $app->post('/categoria/alterar', '\DesafioBackend\controllers\CategoriaController:alterar');
        $app->delete('/categoria/deletar/{id}', '\DesafioBackend\controllers\CategoriaController:deletar');
        $app->get('/categoria/listar/{id}', '\DesafioBackend\controllers\CategoriaController:listarUm');
        $app->get('/categoria/listar', '\DesafioBackend\controllers\CategoriaController:listarTodos');

        $app->post('/produto/adicionar', '\DesafioBackend\controllers\ProdutoController:adicionar');
        $app->post('/produto/alterar', '\DesafioBackend\controllers\ProdutoController:alterar');
        $app->delete('/produto/deletar/{id}', '\DesafioBackend\controllers\ProdutoController:deletar');
        $app->get('/produto/listar/{id}', '\DesafioBackend\controllers\ProdutoController:listarUm');
        $app->get('/produto/listar', '\DesafioBackend\controllers\ProdutoController:listarTodos');

        $app->post('/variante/adicionar', '\DesafioBackend\controllers\VarianteController:adicionar');
        $app->post('/variante/alterar', '\DesafioBackend\controllers\VarianteController:alterar');
        $app->delete('/variante/deletar/{id}', '\DesafioBackend\controllers\VarianteController:deletar');
        $app->get('/variante/listar/{id}', '\DesafioBackend\controllers\VarianteController:listarUm');
        $app->get('/variante/listar', '\DesafioBackend\controllers\VarianteController:listarTodos');
        
        $app->post('/cliente/adicionar', '\DesafioBackend\controllers\ClienteController:adicionar');
        $app->post('/cliente/alterar', '\DesafioBackend\controllers\ClienteController:alterar');
        $app->delete('/cliente/deletar/{id}', '\DesafioBackend\controllers\ClienteController:deletar');
        $app->get('/cliente/listar/{id}', '\DesafioBackend\controllers\ClienteController:listarUm');
        $app->get('/cliente/listar', '\DesafioBackend\controllers\ClienteController:listarTodos');

        $app->post('/endereco/adicionar', '\DesafioBackend\controllers\EnderecoController:adicionar');
        $app->post('/endereco/alterar', '\DesafioBackend\controllers\EnderecoController:alterar');
        $app->delete('/endereco/deletar/{id}', '\DesafioBackend\controllers\EnderecoController:deletar');
        $app->get('/endereco/listar/{id}', '\DesafioBackend\controllers\EnderecoController:listarUm');
        $app->get('/endereco/listar', '\DesafioBackend\controllers\EnderecoController:listarTodos');

        $app->post('/pedido/adicionar', '\DesafioBackend\controllers\PedidoController:adicionar');
    });
};
