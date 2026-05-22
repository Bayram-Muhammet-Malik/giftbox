<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;
use Slim\Views\Twig;

use gift\core\application\usecases\CatalogueService;
use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;

class GetCategorieIDAction extends AbstractAction {

    public function __invoke(Request $rq, Response $rs, array $args): Response {

        $service = new CatalogueService();

        try {
            $categorie = $service->getCategorieById((int)$args['id']);
        } catch (DataErrorException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (NotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'categorieIDView.twig', [
            'id' => $categorie['id'],
            'libelle' => $categorie['libelle'],
            'description' => $categorie['description']
        ]);
    }
}