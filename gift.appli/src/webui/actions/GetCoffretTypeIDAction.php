<?php
declare(strict_types=1);
namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;
use Slim\Views\Twig;

use gift\core\domain\entities\CoffretType;

class GetCoffretTypeIDAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        if (!isset($args['id']) || !ctype_digit((string)$args['id'])) {
            throw new HttpBadRequestException($rq, "ID de coffret type incorrect");
        }
        try {
            $coffretType = CoffretType::where('id', '=', $args['id'])->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new HttpNotFoundException($rq, "ID correspondant non trouvé dans la base de donnée");
        }

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'coffretTypeIDView.twig', [
            'coffret_type' => $coffretType,
            'prestations' => $coffretType->prestations
        ]);
    }
}
