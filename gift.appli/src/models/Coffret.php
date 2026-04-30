<?php
declare(strict_types=1);

namespace gift\appli\models;

use \Illuminate\Database\Eloquent as Eloq;

class Coffret extends Eloq\Model {
    protected $table = 'coffret_type';
    protected $primarykey = 'id';
    public $timestamps = false;

    public function prestation(){
        return $this->belongsToMany('Pestation', 'coffret2presta', 'id', 'id');
    }
}