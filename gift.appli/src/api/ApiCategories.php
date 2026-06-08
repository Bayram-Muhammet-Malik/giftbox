<?php
declare(strict_types=1);

namespace gift\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use gift\core\application\usecases\CatalogueService;
use Slim\Routing\RouteContext;


class ApiCategories
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        $categories = new CatalogueService()->getCategories();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $data = [
            "type" => "collection",
            "count" => count($categories),
            "categories" => [],
        ];

        foreach ($categories as $categorie) {
            $data["categories"][] = [
                "categorie" => [
                    "id" => $categorie["id"],
                    "libelle" => $categorie["libelle"],
                    "description" => $categorie["description"],
                ],
                "links" => [
                    "self" => [
                        "href" => $routeParser->urlFor("api_categories") . "/" . $categorie["id"] . "/",
                    ],
                ],
            ];
        }

        $response->getBody()->write(json_encode($data));

        return $response->withHeader("Content-Type", "application/json");
    }
}
