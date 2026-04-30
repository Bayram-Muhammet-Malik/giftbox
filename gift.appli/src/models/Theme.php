<?php
declare(strict_types=1);

namespace gift\appli\models;

use \Illuminate\Database\Eloquent as Eloq;

class Theme extends Eloq\Model {
    protected $table = 'theme';
    protected $primarykey = 'id';
    public $timestamps = false;

    public function coffret(){
        return $this->hasMany('Coffret', 'id');
    }
}