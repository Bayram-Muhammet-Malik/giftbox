<?php
declare(strict_types=1);

namespace gift\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use gift\core\application\usecases\CatalogueService;
use gift\core\application\exceptions\NotFoundException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpInternalServerErrorException;

class ApiCategories
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        try {    
            $categories = (new CatalogueService())->getCategories();

            $data = [
                'type' => 'collection',
                'count' => count($categories),
                'categories' => []
            ];

            foreach ($categories as $categorie) {
                $data['categories'][] = [
                    'categorie' => [
                        'id' => $categorie['id'],
                        'libelle' => $categorie['libelle'],
                        'description' => $categorie['description']
                    ],
                    'links' => [
                        'self' => [
                            'href' => '/categories/' . $categorie['id'] . '/'
                        ]
                    ]
                ];
            }

            $response->getBody()->write(json_encode($data));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (NotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage());
        }
    }
}
