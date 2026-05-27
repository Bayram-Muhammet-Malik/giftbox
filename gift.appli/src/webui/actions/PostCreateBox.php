<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use gift\core\application\providers\CsrfTokenProvider;
use gift\core\application\exceptions\CsrfException;
use gift\core\application\exceptions\DataErrorException;
use Slim\Exception\HttpBadRequestException;
use gift\core\application\usecases\BoxManaService;
use Slim\Views\Twig;

class PostCreateBox extends AbstractAction
{
      public function __invoke(Request $rq, Response $rs, array $args): Response
      {
            $data = $rq->getParsedBody() ?? [];
            try {
                  CsrfTokenProvider::check($data['csrf_token'] ?? '');
            } catch (CsrfException $e) {
                  throw new HttpBadRequestException($rq, 'Token CSRF invalide');
            }

            $libelle = filter_var($data['libelle'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
            if (empty($libelle)) throw new DataErrorException("Libellé obligatoire");

            try {
                  $box = (new BoxManaService())->createBox($libelle, filter_var($data['description'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS), isset($data['kdo']) && $data['kdo'] === '1', isset($data['kdo']) && $data['kdo'] === '1');
            } catch (DataErrorException $e) {
                  throw new HttpBadRequestException($rq, $e->getMessage());
            }

            $_SESSION['box_id'] = $box['id'];

            $view = Twig::fromRequest($rq);
            return $view->render($rs, 'success.twig', [
                  'box' => $box,
            ]);
      }
}