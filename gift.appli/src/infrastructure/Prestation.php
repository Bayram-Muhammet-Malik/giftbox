<?php
declare(strict_types=1);

namespace gift\appli\infrastructure;

use \Illuminate\Database\Eloquent as Eloq;

class Prestation extends Eloq\Model {
    protected $table = 'prestation';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;
    public $keyType = 'string';

    public function coffret() {
        return $this->belongsToMany(CoffretType::class, 'coffret2presta', 'presta_id', 'coffret_id');
    }
    public function box() {
        return $this->belongsToMany(Box::class, 'box2presta', 'presta_id', 'box_id');
    }

    public function categorie() {
        return $this->belongsTo(Categorie::class, 'cat_id');
    }
}
