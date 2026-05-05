<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// Connexion BD
use Illuminate\Database\Capsule\Manager as DB;
$db = new DB;
$db->addConnection(parse_ini_file(__DIR__ . '/../conf/gift.db.conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\App;

use \gift\appli\controllers\GetCategoriesAction;
use \gift\appli\controllers\GetCategorieIDAction;
use \gift\appli\controllers\GetPrestationIDAction;

return function (App $app): App {
      $app->get('/categories', GetCategoriesAction::class);
      $app->get('/categorie/{id}', GetCategorieIDAction::class);
      $app->get('/prestation',GetPrestationIDAction::class);
      return $app;
};