<?php
declare(strict_types=1);

// Connexion BD
use Illuminate\Database\Capsule\Manager as DB;
$db = new DB;
$db->addConnection(parse_ini_file(__DIR__ . '/../conf/gift.db.conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

return function (App $app): App {
      $app->get('/hello/{name}',
            function(Request $rq, Response $rs,array $args):Response {
                  $rs->getBody()->write("Hello, ". $args['name']);
                  return $rs;
            }
      );

      // Catégories
      $app->get('categories',
            function(Request $rq, Response $rs,array $args):Response {
                  $rs->getBody()->write("Voici la liste des catégories");
                  // Prendre dans la bd la liste des catégories via requete sql
                  $categorie = Categories::all();
                  $html = "<ul>";
                    foreach ($categories as $categorie) {
                        echo "{$categorie->id} - {$categorie->libelle} - {$categorie->description}\n";
                    }
                    $html .= "</ul>";

                    $rs->getBody()->write($html);
                  return $rs;
            }
      );

      // Catégorie : categorie/{id}
      $app -> get('/categorie/{id}',
      function(Request $rq, Response $rs,array $args):Response {
            $rs->getBody()->write("Categorie id : ". $args['id']);

            $categorie = Categories::all();
            return $rs;
      }
      );

      // Prestation : prestation?id=xxxx
      $app->get('prestation/{id}',
            function(Request $rq, Response $rs,array $args):Response {
                  $rs->getBody()->write("Voici la prestation d'id : " . $args['id']);
                  $categorie = Categorie::all()
                  return $rs;
            }
      );

      return $app;
};
