<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use gift\appli\models\Prestation;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

$db = new DB;
$db->addConnection(parse_ini_file(__DIR__ . '/../conf/gift.db.conf.ini'));
$db->setAsGlobal();
$db->bootEloquent();

if ($argc < 2) {
    exit;
}

$id = $argv[1];

try {
    $prestation = Prestation::findOrFail($id);

    echo "{$prestation->id}  - {$prestation->libelle} - {$prestation->description} - {$prestation->tarif} - {$prestation->unite}\n";

} catch (ModelNotFoundException $e) {
    echo "Aucune prestation trouvée avec l'identifiant $id\n";
    exit(1);
}