<?php
declare(strict_types=1);
namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use gift\core\domain\entities\CoffretType;
use Slim\Views\Twig;

class GetCoffretTypeAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $coffretTypes = CoffretType::orderBy('theme_id')->get()->groupBy('theme_id');

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'coffretTypeView.twig', [
            'coffret_types' => $coffretTypes,
        ]);
    }
}
