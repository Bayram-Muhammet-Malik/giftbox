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

use \gift\appli\models\Categorie;

return function (App $app): App {
      // Catégories
      $app->get('/categories',
            function(Request $rq, Response $rs,array $args):Response {
                  $categories = Categorie::all();
                  
                  $html = <<<HTML
                  <!DOCTYPE html>
                  <html lang="fr">
                  <head>
                  \t<meta charset="UTF-8">
                  \t<meta name="viewport" content="width=device-width, initial-scale=1.0">
                  \t<title>Giftbox - Catégories</title>
                  </head>
                  <body>
                  \t<ul>\n
                  HTML;

                  foreach ($categories as $categorie) $html .= "\t\t<li>{$categorie->id} - {$categorie->libelle} - {$categorie->description}</li>\n";
                  
                  $html .= <<<HTML
                  </ul>
                  </body>
                  </html>
                  HTML;

                  $rs->getBody()->write($html);
                  return $rs;
            }
      );

      return $app;
};

      /*
      // Catégorie : categorie/{id}
      $app -> get('/categorie/{id}',
            function(Request $rq, Response $rs,array $args):Response {
                  $rs->getBody()->write("Categorie id : ". $args['id']);

                  $categorie = Categories::all();
                  $html = $headerHMTL . "<ul>";
                  foreach ($categories as $categorie) {
                        echo "{$categorie->id} - {$categorie->libelle} - {$categorie->description}\n";
                  }
                  $html .= "</ul>" . $footerHTML;
                  
                  return $rs;
            }
      );

      // Prestation : prestation?id=xxxx
      $app->get('prestation/{id}',
            function(Request $rq, Response $rs,array $args):Response {
                  $rs->getBody()->write("Voici la prestation d'id : " . $args['id']);
                  $prestation = Prestation::where("id", "=", $args['id']);
                  $html = 
                        "<ul>{$prestation->id} - {$prestation->libelle} - {$prestation->description} - {$prestation->unite} - 
                        {$prestation->tarif} - {$prestation->cat_id}\n</ul>";
                  
                  $rs->getBody()->write($html);
                  return $rs;
            }
      );

      return $app;
      */
