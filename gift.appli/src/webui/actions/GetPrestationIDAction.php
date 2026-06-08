<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;
use Slim\Views\Twig;

use gift\core\application\usecases\CatalogueService;
use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;
use gift\webui\providers\CsrfTokenProvider;

class GetPrestationIDAction extends AbstractAction {

    public function __invoke(Request $rq, Response $rs, array $args): Response {
        $service = new CatalogueService();   
        
        try {
            $prestation = $service->getPrestationById($args['id']);
        } catch (DataErrorException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (NotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

        $token = CsrfTokenProvider::generate();

        $view = Twig::fromRequest($rq);

        return $view->render($rs, 'prestationIDView.twig', [
            'id' => $prestation['id'],
            'libelle' => $prestation['libelle'],
            'description' => $prestation['description'],
            'categorie_id' => $prestation['cat_id'],
            'currentbox' => isset($_SESSION['box_id']),
            'csrf_token' => $token
        ]);
    }
}