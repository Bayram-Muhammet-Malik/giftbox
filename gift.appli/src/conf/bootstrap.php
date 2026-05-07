<?php
declare(strict_types=1);

session_start();

use \gift\appli\utils\Eloquent;

Eloquent::init(__DIR__ . '/gift.db.conf.ini');

$app = \Slim\Factory\AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true,false,false);
$app->setBasePath('/giftbox/gift.appli/public');
$app = (require_once __DIR__ . '/routes.php')($app);
return $app;
