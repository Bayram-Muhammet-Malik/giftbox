<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Exception\HttpBadRequestException;
use Slim\Routing\RouteContext;

use gift\core\application\usecases\BoxUrlService;

class GetAccessBoxAction extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $token = $args['token'] ?? null;
        if ($token === null) throw new HttpBadRequestException($rq, 'Token manquant');

        $service = new BoxUrlService();
        $box = $service->boxUsed($token);

        $view = Twig::fromRequest($rq);
        return $view->render($rs, 'accessBox.twig', ['box' => $box]);
    }
}
