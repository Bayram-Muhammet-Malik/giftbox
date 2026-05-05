<?php
declare(strict_types=1);
namespace gift\appli\controllers;

require_once __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use gift\appli\models\Categorie;

class GetCategorieIDAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $categorie = Categorie::where('id', '=', $args['id'])->first();

        if ($categorie) {
            $content = "<p>{$categorie->id} - {$categorie->libelle} - {$categorie->description}</p>";
        } else {
            $content = "<p>Aucune catégorie trouvée pour l'id : {$args['id']}</p>";
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