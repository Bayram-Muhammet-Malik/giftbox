<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddPrestationToCurrentBoxAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $boxId = $_SESSION['box_id'] ?? null;
        $prestationId = $args['id'] ?? null;

        if ($boxId === null) {
            $response->getBody()->write('Aucune box courante en session');
            return $response->withStatus(400);
        }

        if ($prestationId === null) {
            $response->getBody()->write('Prestation manquante');
            return $response->withStatus(400);
        }

        $service = $this->container->get('box.service');
        $service->addPrestationToBox((int)$boxId, (string)$prestationId);

        return $response
            ->withHeader('Location', '/box/current')
            ->withStatus(302);
    }
}
