<?php
declare(strict_types=1);
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



      $app->get('/prestation/', 
            function(Request $rq, Response $rs,array $args):Response {
                  $queryId = $rq->getQueryParams()[ 'id' ] ;
                  

                  
                  $rs->getBody()->write("Hello, ". $args['name']);
                  return $rs;
            }
      );


      return $app;
};