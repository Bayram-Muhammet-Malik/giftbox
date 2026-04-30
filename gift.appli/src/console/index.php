<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use \gift\appli\models\Categorie;

use Illuminate\Database\Capsule\Manager as DB;
$db = new DB;
$db->addConnection(parse_ini_file(__DIR__ . '/../conf/gift.db.conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$categories = Categorie::all();
foreach ($categories as $categorie) {
    echo $categorie->id . ' - ' . $categorie->libelle . ' - ' . $categorie->description . '<br>';
}