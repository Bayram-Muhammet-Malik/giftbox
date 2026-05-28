<?php
declare(strict_types=1);

session_start();

use \gift\infra\Eloquent;
use \Slim\Views\Twig;
use \Slim\Views\TwigMiddleware;

Eloquent::init(__DIR__ . '/gift.db.conf.ini');

$app = \Slim\Factory\AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true,false,false);
$app->setBasePath('');

// Twig
$twig = Twig::create(__DIR__ . '/../webui/views', [
    'cache' => __DIR__ . '/../app/views/cache',
    'auto_reload' => true,
]);
$twig->getEnvironment()->addGlobal('css_path', $app->getBasePath() . '/css');
$twig->getEnvironment()->addGlobal('img_path', $app->getBasePath() . '/images/img');
$twig->getEnvironment()->addGlobal('menu', [
    ['label' => 'Liste des catégories', 'route' => 'categories']
]);
$twig->getEnvironment()->addGlobal('user', $_SESSION['user'] ?? null);
$app->add(TwigMiddleware::create($app, $twig));

$app = (require_once __DIR__ . '/routes.php')($app);
return $app;
