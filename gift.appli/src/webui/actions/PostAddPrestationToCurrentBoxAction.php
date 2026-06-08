<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\core\application\usecases\BoxManaService;
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

class PostAddPrestationToCurrentBoxAction extends AbstractAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $boxId = $_SESSION['box_id'] ?? null;
        $prestationId = $args['id'] ?? null;
        
        $data = $request->getParsedBody() ?? [];
        try {
                  CsrfTokenProvider::check($data['csrf_token'] ?? '');
            } catch (CsrfException $e) {
                  throw new HttpBadRequestException($request, 'Token CSRF invalide');
            }
        try {
            $createurId = AuthnProvider::getSignedInUser();
        } catch (AuthnException $e) {
            throw new HttpUnauthorizedException($request, 'Non authentifié');
        }

        if($boxId == null && $prestationId == null){
            throw new HttpBadRequestException($request, 'Paramètres non définies');
        }

        //auth et authorization
        $authzService = new AuthzService();
        if (!$authzService->isGranted($createurId, AuthzInterface::CREATE_BOX, $boxId)) {
            throw new HttpForbiddenException($request, 'Action non autorisée');
        }

        $service = new BoxManaService();
        $service->addPrestations($boxId, $prestationId, 1);

        return $response
            ->withHeader('Location', RouteContext::fromRequest($request)->getRouteParser()->urlFor('current_coffret'))->withStatus(302);
    }
}
