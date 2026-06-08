<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

class GetLogoutAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        unset($_SESSION['user']);

        return $response->withHeader(
            'Location',
            RouteContext::fromRequest($request)->getRouteParser()->urlFor('home')
        )->withStatus(302);
    }
}