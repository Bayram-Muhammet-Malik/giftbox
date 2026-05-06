<?php
declare(strict_types=1);
namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use gift\appli\models\Categorie;

class GetCategorieIDAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {

        if (!isset($args['id']) || !ctype_digit($args['id'])) {
            return $this->badRequest($rs, "ID de catégorie invalide");
        }

        $categorie = Categorie::where('id', '=', $args['id'])->first();

        if ($categorie) {
            $content = "<p>{$categorie->id} - {$categorie->libelle} - {$categorie->description}</p>";
        } else if(!$categorie) {
           return $this->badRequest($rs, "Catégorie introuvable");
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
