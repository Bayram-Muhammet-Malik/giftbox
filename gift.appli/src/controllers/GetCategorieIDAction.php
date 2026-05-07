<?php
declare(strict_types=1);
namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;
use Slim\Views\Twig;

use gift\appli\models\Categorie;

class GetCategorieIDAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {

        if (!ctype_digit($args['id'])) throw new HttpBadRequestException($rq, "ID de catégorie incorrecte");

        try {
            $categorie = Categorie::where('id', '=', $args['id'])->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new HttpNotFoundException($rq, "ID correspondant non trouvé dans la base de donnée");
        }

        $view = Twig::fromRequest($rq);

                return $view->render($rs, 'categorieView.twig', [
                    'id' => $categorie->id,
                    'libelle' => $categorie->libelle,
                    'description' => $categorie->description
                ]);
    }

}
