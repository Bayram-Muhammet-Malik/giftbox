<?php
declare(strict_types=1);

session_start();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require_once __DIR__ . '/../src/vendor/autoload.php';


$app = \Slim\Factory\AppFactory::create();
$app->addRoutingMiddleware();
$app->setBasePath('/giftbox/gift.appli/public'); // si install dans un sous répertoire
$app = (require_once __DIR__ . '/../src/conf/routes.php')($app);
$app->run();
