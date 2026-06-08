<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\core\application\usecases\BoxManaService;
use gift\core\application\usecases\AuthzService;
use gift\core\application\usecases\AuthzInterface;
use gift\webui\providers\AuthnProvider;
use Slim\Exception\HttpForbiddenException;

class PostAddPrestationToCurrentBoxAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $boxId = $_SESSION['box_id'] ?? null;
        $createurId = $_SESSION['id'] ?? null;
        $prestationId = $args['id'] ?? null;

        if ($boxId === null) {
            $response->getBody()->write('Aucune box courante en session');
            return $response->withStatus(400);
        }

        if ($createurId === null) {
            $response->getBody()->write('Utilisateur non connecté');
            return $response->withStatus(401);
        }

        if ($prestationId === null) {
            $response->getBody()->write('Prestation manquante');
            return $response->withStatus(400);
        }

        //auth et authorization
        $authzService = new AuthzService();
        if (!$authzService->isGranted(AuthnProvider::getSignedInUser()['user_id'], AuthzInterface::CREATE_BOX, $boxId)) {
            throw new HttpForbiddenException($request, 'Action non autorisée');
        }

        $service = new BoxManaService();
        $service->addPrestations((int)$boxId, (int)$prestationId, 1);

        return $response
            ->withHeader('Location', '/box/current')
            ->withStatus(302);
    }
}
