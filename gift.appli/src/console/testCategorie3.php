<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use \gift\appli\models\Prestation;
use \gift\appli\models\Categorie;

use Illuminate\Database\Capsule\Manager as DB;
$db = new DB;
$db->addConnection(parse_ini_file(__DIR__ . '/../conf/gift.db.conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$categorie3 = Categorie::where('id', '=', 3)->first();
echo "{$categorie3->id} - {$categorie3->libelle} - {$categorie3->description}\n";
foreach ($categorie3->prestation as $presta) {
    echo "> {$presta->id} - {$presta->libelle} - {$presta->description} - {$presta->tarif} - {$presta->unite}\n";
}