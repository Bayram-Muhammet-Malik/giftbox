<?php
declare(strict_types=1);
namespace gift\appli\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractAction {
    abstract public function __invoke(Request $rq, Response $rs, array $args): Response;

    protected function badRequest(Response $rs, string $message): Response {
        $rs->getBody()->write(json_encode(['error' => $message]));
        return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
}
