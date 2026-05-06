<?php
declare(strict_types=1);
namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

use gift\appli\models\Categorie;

class GetCategorieIDAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $categorie = Categorie::where('id', '=', $args['id'])->first();

        if ($categorie) {
            $content = "<p>{$categorie->id} - {$categorie->libelle} - {$categorie->description}</p>";
        } else {
            throw new HttpNotFoundException($rq,"id non présente dans la base de donnée");
            //$content = "<p>Aucune catégorie trouvée pour l'id : {$args['id']}</p>";
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

    protected function badRequest(Response $rs, string $message): Response {
        $rs->getBody()->write(json_encode(['error' => $message]));
        return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
}
