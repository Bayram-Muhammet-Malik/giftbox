<?php
declare(strict_types=1);

namespace gift\appli\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;

use Slim\Views\Twig;
use gift\appli\infrastructure\Categorie;

class GetPrestationsCategorieAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {

        if (!ctype_digit($args['id'])) throw new HttpBadRequestException($rq, "ID de catégorie incorecte");

        try {
            $categorie = Categorie::where('id', '=', $args['id'])->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new HttpNotFoundException($rq, "ID correspondant non trouvé dans la base de donnée");
        }

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'categoriePrestationsView.twig', [
            'prestations' => $prestations = $categorie->prestations,
            'categorie_id' => $categorie->id
        ]);
    }
}
