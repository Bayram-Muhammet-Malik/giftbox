<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use gift\webui\providers\CsrfTokenProvider;

class GetCreateBoxForm extends AbstractAction
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $token = CsrfTokenProvider::generate();

        $view = Twig::fromRequest($rq);
        return $view->render($rs, 'coffretCreate.twig', [
            'csrf_token' => $token
        ]);
    }
}