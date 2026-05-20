<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;

use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueService;

class GetCategorieIDPrestationsAction extends AbstractAction {

    public function __invoke(Request $rq, Response $rs, array $args): Response {

        if (!ctype_digit($args['id'])) throw new HttpBadRequestException($rq, "ID de catégorie incorrecte");

        $service = new CatalogueService();
        $id = (int)$args['id'];

        $categorie = $service->getCategorieById($id);
        if (empty($categorie)) throw new HttpNotFoundException($rq, "ID correspondant non trouvé dans la base de donnée");

        $prestations = $service->getPrestationsByCategorie($id);

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'categorieIDPrestationsView.twig', [
            'prestations'   => $prestations,
            'categorie_id'  => $categorie['id']
        ]);
    }
}
