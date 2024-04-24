

<?php

use Slim\Factory\AppFactory;
use Slim\Http\UploadedFile;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../src/config/dependencias.php';
AppFactory::setContainer($container);

$app = AppFactory::create();

$routes = require __DIR__ . '/../src/routes/routes.php';
$routes($app);



$app->setBasePath('/api');
$app->run();
?>
