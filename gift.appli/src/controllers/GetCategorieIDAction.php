<?php
declare(strict_types=1);
namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;

use gift\appli\models\Categorie;

class GetCategorieIDAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {

        if (!ctype_digit($args['id'])) throw new HttpBadRequestException($rq, "ID de catégorie incorecte");

        try {
            $categorie = Categorie::where('id', '=', $args['id'])->firstOrFail();
            $content = "<p>{$categorie->id} - {$categorie->libelle} - {$categorie->description}</p>";
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new HttpNotFoundException($rq, "ID correspondant non trouvé dans la base de donnée");
        }

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Giftbox - Catégorie ID</title>
        </head>
        <body>
            {$content}
        </body>
        </html>
        HTML;

        $rs->getBody()->write($html);
        return $rs;
    }

}
