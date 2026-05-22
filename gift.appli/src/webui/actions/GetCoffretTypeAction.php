<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueService;

class GetCoffretTypeAction extends AbstractAction {

    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $service = new CatalogueService();
        $coffretTypes = $service->getCoffretTypes();
        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'coffretTypeView.twig', [
            'coffret_types' => $coffretTypes,
        ]);
    }
}