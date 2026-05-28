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

class SigninAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        if ($request->getMethod() === 'GET') {

            return Twig::fromRequest($request)
                ->render($response, 'signinView.twig', [
                    'csrf' => CsrfTokenProvider::generate()
                ]);
        }

        $data = $request->getParsedBody();

        $user_id = filter_var(
            $data['user_id'] ?? '',
            FILTER_SANITIZE_EMAIL
        );

        $password = $data['password'] ?? '';

        $csrf = $data['csrf'] ?? '';

        try {

            CsrfTokenProvider::check($csrf);

            $provider = new AuthnProvider(
                new AuthnService()
            );

            $provider->signin($user_id, $password);

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);

        } catch (NotFoundException | DataErrorException $e) {

            return Twig::fromRequest($request)
                ->render($response, 'signinView.twig', [
                    'error' => $e->getMessage(),
                    'csrf' => CsrfTokenProvider::generate()
                ]);
        }
    }
}
//login
//aurore06@example.org
//aurore06
