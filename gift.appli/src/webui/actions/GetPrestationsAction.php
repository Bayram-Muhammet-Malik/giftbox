<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueService;

class GetPrestationsAction extends AbstractAction {

    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $service = new CatalogueService();
        $prestations = $service->getPrestations();
        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'prestationView.twig', [
            'prestations' => $prestations,
        ]);
    }
}