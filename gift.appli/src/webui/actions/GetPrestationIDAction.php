<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueService;

class GetPrestationIDAction extends AbstractAction {

    public function __invoke(Request $rq, Response $rs, array $args): Response {

        $id = $args['id'] ?? null;

        if ($id === null) {
            throw new HttpBadRequestException($rq, "ID de prestation manquant");
        }

        $service = new CatalogueService();    
        $prestation = $service->getPrestationById((string) $id);

        if (empty($prestation)) {
            throw new HttpNotFoundException($rq, "ID correspondant non trouvé dans la base de donnée");
        }

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'prestationIDView.twig', [
            'id' => $prestation['id'],
            'libelle' => $prestation['libelle'],
            'description' => $prestation['description'],
            'categorie_id' => $prestation['cat_id']
        ]);
    }
}