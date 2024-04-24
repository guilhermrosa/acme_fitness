
<?php
use DI\ContainerBuilder;
use DesafioBackend\dao\VarianteDao;
use DesafioBackend\dao\ProdutoDao;
use DesafioBackend\dao\CategoriaDao;
use DesafioBackend\dao\EnderecoDao;
use DesafioBackend\dao\ClienteDao;
use DesafioBackend\dao\PedidoDao;
use DesafioBackend\controllers\VarianteController;
use DesafioBackend\controllers\ProdutoController;
use DesafioBackend\controllers\CategoriaController;
use DesafioBackend\controllers\EnderecoController;
use DesafioBackend\controllers\ClienteController;
use DesafioBackend\controllers\PedidoController;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    CategoriaController::class => function ($container) {
        $catDao = $container->get(CategoriaDao::class);
        return new CategoriaController($catDao);
    },
    ProdutoController::class => function ($container) {
        $proDao = $container->get(ProdutoDao::class);
        $catDao = $container->get(CategoriaDao::class);
        return new ProdutoController($proDao, $catDao);
    },
    VarianteController::class => function ($container) {
        $varDao = $container->get(VarianteDao::class);
        $proDao = $container->get(ProdutoDao::class);
        return new VarianteController($varDao, $proDao);
    },
    ClienteController::class => function ($container) {
        $cliDao = $container->get(ClienteDao::class);
        return new ClienteController($cliDao);
    },
    EnderecoController::class => function ($container) {
        $endDao = $container->get(EnderecoDao::class);
        $cliDao = $container->get(ClienteDao::class);
        return new EnderecoController($endDao, $cliDao);
    },
    PedidoController::class => function ($container) {
        $pedDao = $container->get(PedidoDao::class);
        $cliDao = $container->get(ClienteDao::class);
        $endDao = $container->get(EnderecoDao::class);
        $varDao = $container->get(VarianteDao::class);
        return new PedidoController($pedDao, $cliDao, $endDao, $varDao);
    }
]);

return $containerBuilder->build();
?>