<?php
declare(strict_types=1);
namespace gift\appli\controllers;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use gift\appli\models\Categorie;

class GetCategoriesAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $categories = Categorie::all();

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Giftbox - Catégories</title>
        </head>
        <body>
            <ul>
        HTML;

        foreach ($categories as $categorie) $html .= "\t\t<li>{$categorie->id} - {$categorie->libelle} - {$categorie->description}</li>\n";

        $html .= <<<HTML
            </ul>
        </body>
        </html>
        HTML;

        $rs->getBody()->write($html);
        return $rs;
    }

}
