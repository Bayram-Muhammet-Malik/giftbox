<?php
declare(strict_types=1);

namespace gift\appli\models;

use \Illuminate\Database\Eloquent as Eloq;

class Prestation extends Eloq\Model {
    protected $table = 'prestation';
    protected $primarykey = 'id';
    public $timestamps = false;
    public $incrementing = false;
    public $keyType = 'string';

    public function coffret(){
        return $this->belongsToMany('Coffret','coffret2presta', 'id', 'id');
    }
    public function box(){
        return $this->belongsToMany('Box','box2presta', 'id', 'id');
    }
}