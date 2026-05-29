<?php
declare(strict_types=1);

namespace gift\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use gift\core\application\usecases\CatalogueService;
use Slim\Routing\RouteContext;

class ApiCoffretId {
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args,
    ): ResponseInterface {
        $id = (int) $args['id'];
               $box = (new CatalogueService())->getCoffretById($id);

               $data = [
                   'type' => 'resource',
                   'box' => $box
               ];


        $response->getBody()->write(json_encode($data));

        return $response->withHeader("Content-Type", "application/json");
    }
}
