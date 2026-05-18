<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// Connexion BD
use gift\appli\utils\Eloquent;
Eloquent::init(__DIR__ . '/gift.db.conf.ini');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\App;

use \gift\appli\controllers\GetCategoriesAction;
use \gift\appli\controllers\GetCategorieIDAction;
use \gift\appli\controllers\GetPrestationIDAction;
use \gift\appli\controllers\GetPrestationsCategorieAction;
use \gift\appli\controllers\GetHomeAction;
use \gift\appli\controllers\GetCoffretTypeAction;
use \gift\appli\controllers\GetCoffretTypeIDAction;

return function (App $app): App {
    $app->get('/categories', GetCategoriesAction::class)->setName('categories');
    $app->get('/categorie/{id}', GetCategorieIDAction::class)->setName('categorie_id');
    $app->get('/prestation/{id}', GetPrestationIDAction::class)->setName('prestation');
    $app->get('/categorie/{id}/prestations', GetPrestationsCategorieAction::class)->setName('categorie_prestations');

    $app->get('/', GetHomeAction::class)->setName('home');
    $app->get('/coffret_types', GetCoffretTypeAction::class)->setName('coffret_types');
    $app->get('/coffret_type/{id}', GetCoffretTypeIDAction::class)->setName('coffret_type_detail');

    return $app;
};
