<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpNotFoundException;

use gift\core\application\usecases\BoxManaService;
use gift\webui\providers\AuthnProvider;
use gift\core\application\usecases\AuthzService;

use gift\core\application\exceptions\AuthnException;
use gift\core\application\exceptions\BoxNotFoundException;
use gift\core\application\exceptions\DataErrorException;

class GetCurrentBox extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        try {
            $userId = AuthnProvider::getSignedInUser();
        } catch (AuthnException $e) {
            throw new HttpUnauthorizedException($rq, 'Non authentifié');
        }

        $boxId = $_SESSION['box_id'] ?? null;
        if ($boxId === null)  throw new HttpNotFoundException($rq, "Aucun coffret sélectionné");

        try {
            $box = (new BoxManaService())->getBox($_SESSION['box_id']);
        } catch (BoxNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (DataErrorException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

        $authz = new AuthzService();
        if (!$authz->isGranted($userId, AuthzService::VIEW_BOX, $_SESSION['box_id'])) {
            throw new HttpForbiddenException($rq, 'Action non autorisée');
        }

        $view = Twig::fromRequest($rq);
        return $view->render($rs, 'currentBox.twig', [
            'box' => $box,
        ]);
    }
}
