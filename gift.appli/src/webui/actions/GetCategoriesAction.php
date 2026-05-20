<?php
declare(strict_types=1);
namespace gift\appli\webui\actions;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use gift\appli\infrastructure\Categorie;
use Slim\Views\Twig;

class GetCategoriesAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $categories = Categorie::all();

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'categorieView.twig', [
            'categories' => $categories,
        ]);
    }
}
