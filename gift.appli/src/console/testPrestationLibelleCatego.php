<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use \gift\appli\models\Prestation;

use Illuminate\Database\Capsule\Manager as DB;
$db = new DB;
$db->addConnection(parse_ini_file(__DIR__ . '/../conf/gift.db.conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

//$prestations = Prestation::all();
$prestations = Prestation::with('categorie')->get(); //avec chargement lié

foreach ($prestations as $prestation) {
    echo "\n{$prestation->id} - {$prestation->libelle} - {$prestation->description} - {$prestation->tarif} - {$prestation->unite}\n";
    if ($prestation->categorie) {
        echo "> Categorie : {$prestation->categorie->libelle}\n";
    }
}