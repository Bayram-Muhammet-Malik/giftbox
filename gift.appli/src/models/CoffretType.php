<?php
declare(strict_types=1);

namespace gift\appli\models;

use \Illuminate\Database\Eloquent as Eloq;

class CoffretType extends Eloq\Model {
    protected $table = 'coffret_type';
    protected $primarykey = 'id';
    public $timestamps = false;

    public function prestation() {
        return $this->belongsToMany(Prestation::class, 'coffret2presta', 'coffret_id', 'presta_id');
    }

    public function theme() {
        return $this->belongsTo(Theme::class, 'theme_id');
    }
}