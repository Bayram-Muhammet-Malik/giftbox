<?php
declare(strict_types=1);

namespace gift\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use gift\core\application\usecases\CatalogueService;
use gift\core\application\exceptions\NotFoundException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

class ApiCategorieIDPrestations {
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        try {
            $id = (int) $args['id'];
            $prestations = (new CatalogueService())->getPrestationsByCategorie($id);

            $data = [
                'type' => 'collection',
                'count' => count($prestations),
                'prestations' => $prestations
            ];

            $response->getBody()->write(json_encode($data));
            return $response->withHeader("Content-Type", "application/json");

        } catch (NotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage());
        }
    }
}
