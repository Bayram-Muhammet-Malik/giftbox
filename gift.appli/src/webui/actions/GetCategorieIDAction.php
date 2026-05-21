<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueService;
class GetCategorieIDAction extends AbstractAction {

    public function __invoke(Request $rq, Response $rs, array $args): Response {

        if (!isset($args['id']) || !ctype_digit($args['id'])) {
            throw new HttpBadRequestException($rq, "ID de catégorie incorrect");
        }
        $service = new CatalogueService();
        $categorie = $service->getCategorieById((int)$args['id']);
        if (empty($categorie)) {
            throw new HttpNotFoundException($rq, "ID correspondant non trouvé dans la base de données");
        }
        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'categorieIDView.twig', [
            'id' => $categorie['id'],
            'libelle' => $categorie['libelle'],
            'description' => $categorie['description']
        ]);
    }
}