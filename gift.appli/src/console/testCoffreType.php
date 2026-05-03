<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use \gift\appli\models\CoffretType;

use Illuminate\Database\Capsule\Manager as DB;
$db = new DB;
$db->addConnection(parse_ini_file(__DIR__ . '/../conf/gift.db.conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

$coffretTypes = CoffretType::with('prestation')->get();
foreach ($coffretTypes as $coffretType) {
    echo "\n{$coffretType->id} - {$coffretType->libelle} - {$coffretType->description}\nPrestations :\n";
    foreach ($coffretType->prestation as $presta) {
        echo "> {$presta->libelle}\n";
    }
}