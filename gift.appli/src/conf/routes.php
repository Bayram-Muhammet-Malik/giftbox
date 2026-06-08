<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// Connexion BD
use gift\infra\Eloquent;
Eloquent::init(__DIR__ . '/gift.db.conf.ini');

use \Slim\App;

use \gift\webui\actions\GetCategoriesAction;
use \gift\webui\actions\GetCategorieIDAction;
use \gift\webui\actions\GetPrestationsAction;
use \gift\webui\actions\GetPrestationIDAction;
use \gift\webui\actions\GetCategorieIDPrestationsAction;
use \gift\webui\actions\GetHomeAction;
use \gift\webui\actions\GetCoffretTypeAction;
use \gift\webui\actions\GetCoffretTypeIDAction;
use \gift\webui\actions\GetCreateBoxForm;
use \gift\webui\actions\PostCreateBox;
use \gift\webui\actions\GetCurrentBoxAction;
use \gift\webui\actions\PostAddPrestationToCurrentBoxAction;
use \gift\webui\actions\PostValidateBoxAction;
use \gift\webui\actions\PostGenerateUrlBoxAction;
use \gift\webui\actions\GetAccessBoxAction;

use \gift\webui\actions\SigninAction;
use \gift\webui\actions\SignupAction;
use \gift\webui\actions\GetLogoutAction;

use \gift\api\ApiCategories;
use \gift\api\ApiCoffretId;
use \gift\api\ApiPrestations;
use \gift\api\ApiCategorieIDPrestations;

return function (App $app): App {
    $app->get('/', GetHomeAction::class)->setName('home');
    $app->get('/categories', GetCategoriesAction::class)->setName('categories');
    $app->get('/categorie/{id}', GetCategorieIDAction::class)->setName('categorie_id');
    $app->get('/prestations', GetPrestationsAction::class)->setName('prestations');
    $app->get('/prestation/{id}', GetPrestationIDAction::class)->setName('prestation_id');
    $app->get('/categorie/{id}/prestations', GetCategorieIDPrestationsAction::class)->setName('categorie_prestations');
    $app->get('/coffret_types', GetCoffretTypeAction::class)->setName('coffret_types');
    $app->get('/coffret_type/{id}', GetCoffretTypeIDAction::class)->setName('coffret_type_detail');

    $app->get('/box/create', GetCreateBoxForm::class)->setName('create_coffret');
    $app->post('/box/create', PostCreateBox::class);
    $app->get('/box/current', GetCurrentBoxAction::class)->setName('current_coffret');
    $app->post('/box/validate/', PostValidateBoxAction::class)->setName('validate_box');
    $app->post('/box/generate/', PostGenerateUrlBoxAction::class)->setName('generate_url_box');
    $app->get('/box/access/{token}', GetAccessBoxAction::class)->setName('access_box');

    $app->post('/prestation/{id}', PostAddPrestationToCurrentBoxAction::class)->setName('box_add_prestation');

    $app->map(['GET', 'POST'], '/signin', SigninAction::class)->setName('signin');
    $app->map(['GET', 'POST'], '/signup', SignupAction::class)->setName('signup');
    $app->get('/logout', GetLogoutAction::class)->setName('logout');

    $app->get('/api/categories', ApiCategories::class )->setName('api_categories');
    $app->get('/api/prestations', ApiPrestations::class )->setName('api_prestations');
    $app->get('/api/boxes/{id}', ApiCoffretId::class )->setName('api_coffret_id');
    $app->get('/api/categorie/{id}/prestations', ApiCategorieIDPrestations::class )->setName('api_categorie_id_prestations');

    return $app;
};
