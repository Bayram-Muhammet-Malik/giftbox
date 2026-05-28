<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// Connexion BD
use gift\infra\Eloquent;
use gift\webui\actions\GetFormulaire;
Eloquent::init(__DIR__ . '/gift.db.conf.ini');

use \Slim\App;

use \gift\webui\actions\GetCategoriesAction;
use \gift\webui\actions\GetCategorieIDAction;
use \gift\webui\actions\GetPrestationIDAction;
use \gift\webui\actions\GetCategorieIDPrestationsAction;
use \gift\webui\actions\GetHomeAction;
use \gift\webui\actions\GetCoffretTypeAction;
use \gift\webui\actions\GetCoffretTypeIDAction;
use \gift\webui\actions\GetCreateBoxForm;
use \gift\webui\actions\PostCreateBox;
use \gift\webui\actions\SigninAction;
use \gift\webui\actions\AddPrestationToCurrentBoxAction;
use \gift\webui\actions\LogoutAction;

return function (App $app): App {
    $app->get('/categories', GetCategoriesAction::class)->setName('categories');
    $app->get('/categorie/{id}', GetCategorieIDAction::class)->setName('categorie_id');
    $app->get('/prestation/{id}', GetPrestationIDAction::class)->setName('prestation');
    $app->get('/categorie/{id}/prestations', GetCategorieIDPrestationsAction::class)->setName('categorie_prestations');

    $app->get('/', GetHomeAction::class)->setName('home');
    $app->get('/coffret_types', GetCoffretTypeAction::class)->setName('coffret_types');
    $app->get('/coffret_type/{id}', GetCoffretTypeIDAction::class)->setName('coffret_type_detail');
    $app->get('/box/create', GetCreateBoxForm::class)->setName('create_coffret');
    $app->post('/box/create', PostCreateBox::class);

    $app->post('/box/add/prestation/{id}', AddPrestationToCurrentBoxAction::class)->setName('box_add_prestation');

    $app->get('/box/current', DisplayCurrentBoxAction::class)->setName('box_current');
    $app->post('/box/validate', ValidateCurrentBoxAction::class)->setName('box_validate');

    $app->map(['GET', 'POST'], '/signin', SigninAction::class)->setName('signin');

    $app->get('/logout', LogoutAction::class)->setName('logout');

    return $app;
};
