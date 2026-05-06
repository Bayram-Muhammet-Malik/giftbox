<?php
declare(strict_types=1);

session_start();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \gift\appli\utils\Eloquent;

require_once __DIR__ . '/../src/vendor/autoload.php';

Eloquent::init(__DIR__ . '/../src/conf/gift.db.conf.ini');

$app = \Slim\Factory\AppFactory::create();
$app->addRoutingMiddleware();
$app->setBasePath('/giftbox/gift.appli/public'); // si install dans un sous répertoire
$app = (require_once __DIR__ . '/../src/conf/routes.php')($app);
$app->run();
