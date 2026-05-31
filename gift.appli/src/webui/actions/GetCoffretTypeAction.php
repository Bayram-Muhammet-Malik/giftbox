<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\core\application\usecases\CatalogueService;
use gift\core\application\usecases\AuthzService;
use gift\core\application\usecases\AuthzInterface;
use gift\webui\providers\AuthnProvider;
use Slim\Exception\HttpForbiddenException;

class GetCoffretTypeAction extends AbstractAction {

    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $authzService = new AuthzService();
        if (!$authzService->isGranted(AuthnProvider::getSignedInUser(), AuthzInterface::CREATE_BOX, $_SESSION['box_id'])) {
            throw new HttpForbiddenException($rq, 'Action non autorisée');
        }
        
        $service = new CatalogueService();
        $coffretTypes = $service->getCoffretTypes();
        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'coffretTypeView.twig', [
            'coffret_types' => $coffretTypes,
        ]);
    }
}