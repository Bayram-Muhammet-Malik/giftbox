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
use \gift\appli\models\Prestation;

return function (App $app): App {
      $app->get('/categories', GetCategoriesAction::class);
      $app->get('/categorie/{id}', GetCategorieIDAction::class);

      // prestation?id=xxxx
      $app->get('/prestation',
            function(Request $rq, Response $rs): Response {
                  $id = $rq->getQueryParams()['id'] ?? null;
                  
                  if ($id === null || $id === ''){
                        $content = "<p>Aucun id donné</p>";
                  } else {
                        $prestation = Prestation::find($id);

                        if ($prestation) {
                              $content = "<p>{$prestation->id} - {$prestation->libelle} - {$prestation->description}</p>";
                        } else {
                              $content = "<p>Aucune prestation trouvée pour l'id : {$id}</p>";
                        }
                  }

                  $html = <<<HTML
                  <!DOCTYPE html>
                  <html lang="fr">
                  <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Giftbox - Prestation ID</title>
                  </head>
                  <body>
                        {$content}
                  </body>
                  </html>
                  HTML;

                  $rs->getBody()->write($html);
                  return $rs;
            }
      );

      return $app;
};