<?php
declare(strict_types=1);
namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;

use Slim\Views\Twig;
use gift\appli\models\Prestation;

class GetPrestationIDAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $id = $args['id'] ?? null;
      if ($id === null) throw new HttpBadRequestException($rq, "ID de prestation manquant");

      try {
            $prestation = Prestation::findOrFail($id);
            $content = "<p>{$prestation->id} - {$prestation->libelle} - {$prestation->description}</p>";
      } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new HttpNotFoundException($rq,"ID correspondant non trouvé dans la base de donnée");
      }

      $view = Twig::fromRequest($rq);

      return $view->render($rs, 'prestationIDView.twig', [
          'id' => $prestation->id,
          'libelle' => $prestation->libelle,
          'description' => $prestation->description,
          'categorie_id' => $prestation->categorie_id
      ]);
    }
}
