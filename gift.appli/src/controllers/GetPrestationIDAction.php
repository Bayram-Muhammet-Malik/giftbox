<?php
declare(strict_types=1);
namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

use gift\appli\models\Prestation;

class GetPrestationIDAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
       $id = $rq->getQueryParams()['id'] ?? null;

      if ($id === null || $id === ''){
            $content = "<p>Aucun id donné</p>";
      } else {
            $prestation = Prestation::find($id);

            if ($prestation) {
                  $content = "<p>{$prestation->id} - {$prestation->libelle} - {$prestation->description}</p>";
            } else {
                  throw new HttpNotFoundException($rq,"id non présente dans la base de donnée");
                  //$content = "<p>Aucune prestation trouvée pour l'id : {$id}</p>";
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

      protected function badRequest(Response $rs, string $message): Response {
        $rs->getBody()->write(json_encode(['error' => $message]));
        return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
}
