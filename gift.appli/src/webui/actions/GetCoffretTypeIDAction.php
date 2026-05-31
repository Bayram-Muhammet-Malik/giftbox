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
use gift\core\application\usecases\AuthzService;
use gift\core\application\usecases\AuthzInterface;
use gift\webui\providers\AuthnProvider;
use Slim\Exception\HttpForbiddenException;

class GetCoffretTypeIDAction extends AbstractAction {

    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $authzService = new AuthzService();
        if (!$authzService->isGranted(AuthnProvider::getSignedInUser(), AuthzInterface::CREATE_BOX, $_SESSION['box_id'])) {
            throw new HttpForbiddenException($rq, 'Action non autorisée');
        }
        $service = new CatalogueService();

        try {
            $coffretType = $service->getCoffretById((int) $args['id']);
        } catch (DataErrorException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (NotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'coffretTypeIDView.twig', [
            'coffret_type' => $coffretType,
            'prestations' => $coffretType['prestations'] ?? []
        ]);
    }
}