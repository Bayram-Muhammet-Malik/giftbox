<?php
declare(strict_types=1);
namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;

use gift\appli\models\Prestation;

class GetPrestationIDAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
      $id = $rq->getQueryParams()['id'] ?? null;
      if ($id === null) throw new HttpBadRequestException($rq, "ID de prestation manquant");
      
      try {
            $prestation = Prestation::findOrFail($id);
            $content = "<p>{$prestation->id} - {$prestation->libelle} - {$prestation->description}</p>";
      } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new HttpNotFoundException($rq,"ID correspondant non trouvé dans la base de donnée");
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
}
