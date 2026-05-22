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

class GetCategorieIDPrestationsAction extends AbstractAction
{

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id = (int) $args['id'];
        $service = new CatalogueService();

        try {
            $categorie = $service->getCategorieById($id);
        } catch (DataErrorException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (NotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

        $prestations = $service->getPrestationsByCategorie($id);

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'categorieIDPrestationsView.twig', [
            'prestations' => $prestations,
            'categorie_id' => $categorie['id']
        ]);
    }
}