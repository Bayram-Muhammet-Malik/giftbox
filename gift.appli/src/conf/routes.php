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

return function (App $app): App {
      $app->get('/categories', GetCategoriesAction::class)->setName('categories');
      $app->get('/categorie/{id}', GetCategorieIDAction::class)->setName('categorieID');
      $app->get('/prestation',GetPrestationIDAction::class)->setName('prestation');
      $app->get('/categorie/{id}/prestations', GetPrestationsCategorieAction::class)->setName('categ2prestas');
      return $app;

};
