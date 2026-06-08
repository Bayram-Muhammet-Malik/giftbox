<?php
declare(strict_types=1);

namespace gift\webui\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

use gift\webui\providers\AuthnProvider;
use gift\webui\providers\CsrfTokenProvider;
use gift\core\application\usecases\AuthnService;
use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;
use gift\core\application\exceptions\AuthnException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

class SignupAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        if ($request->getMethod() === 'GET') {
            return Twig::fromRequest($request)
                ->render($response, 'signinupView.twig', [
                    'page_title' => 'Inscription',
                    'form_action' => '/signup',
                    'button_label' => "S'inscrire",
                    'alt_text' => 'Déjà un compte ?',
                    'alt_link' => '/signin',
                    'alt_link_label' => 'Connectez-vous ici',
                    'csrf' => CsrfTokenProvider::generate()
                ]);
        } else if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();

            $user_id = filter_var($data['user_id'] ?? '', FILTER_SANITIZE_EMAIL);
            $password = $data['password'] ?? '';
            $csrf = $data['csrf'] ?? '';

            try {

                CsrfTokenProvider::check($csrf);
                AuthnProvider::register($user_id, $password);

                return $response->withHeader('Location', '/')->withStatus(302);

            } catch (NotFoundException $e) {
                throw new HttpNotFoundException($request, $e->getMessage());

            } catch (DataErrorException $e) {
                throw new HttpBadRequestException($request, $e->getMessage());

            } catch (AuthnException $e) {
                throw new HttpUnauthorizedException($request, $e->getMessage());
            }
        }
    }
}
