<?php
declare(strict_types=1);

namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use gift\appli\models\Categorie;

class GetPrestationsCategorieAction extends AbstractAction {
    public function __invoke(Request $rq, Response $rs, array $args): Response {
        
        if (!isset($args['id']) || !ctype_digit($args['id'])) {
            return $this->badRequest($rs, "ID de catégorie invalide");
        }

        $categorie = Categorie::where('id', '=', $args['id'])->first();

        if ($categorie) {
            $content = "<h2>Prestations de la catégorie : {$categorie->libelle}</h2>";

            if ($categorie->prestations !== null) {
                if (count($categorie->prestations) > 0) {
                    $content .= "<ul>";

                    foreach ($categorie->prestations as $prestation) {
                        $content .= "<li>{$prestation->id} - {$prestation->libelle} - {$prestation->description}</li>";
                    }

                    $content .= "</ul>";
                } else {
                    $content .= "<p>Aucune prestation dans cette catégorie</p>";
                }
            } else {
                $content .= "<p>Relation prestations non trouvée</p>";
            }

        } else {
            return $this->badRequest($rs, "Catégorie introuvable");
        }

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Giftbox - Prestations Catégorie</title>
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
