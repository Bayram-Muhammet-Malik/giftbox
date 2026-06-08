<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\core\application\usecases\BoxUrlService;
use gift\core\application\usecases\AuthzService;
use gift\core\application\usecases\AuthzInterface;
use gift\webui\providers\AuthnProvider;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use gift\core\application\exceptions\AuthnException;
use Slim\Routing\RouteContext;
use gift\core\application\exceptions\CsrfException;
use gift\webui\providers\CsrfTokenProvider;

class PostGenerateUrlBoxAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $boxId = $_SESSION['box_id'] ?? null;
        if ($boxId === null) throw new HttpBadRequestException($rq, 'Aucune box courante définie');

        $data = $rq->getParsedBody() ?? [];
        try {
            CsrfTokenProvider::check($data['csrf_token'] ?? '');
        } catch (CsrfException $e) {
            throw new HttpBadRequestException($rq, 'Token CSRF invalide');
        }

        try {
            $userId = AuthnProvider::getSignedInUser();
        } catch (AuthnException $e) {
            throw new HttpUnauthorizedException($rq, 'Non authentifié');
        }

        $authz = new AuthzService();
        if (!$authz->isGranted($userId, AuthzInterface::GENERATE_URL, $boxId)) throw new HttpForbiddenException($rq, 'Action non autorisée');

        $service = new BoxUrlService();
        $service->deliverBox($boxId);

        return $rs->withHeader('Location', RouteContext::fromRequest($rq)->getRouteParser()->urlFor('current_coffret'))->withStatus(302);
    }
}
