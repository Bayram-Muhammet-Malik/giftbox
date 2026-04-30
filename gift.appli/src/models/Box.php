<?php
declare(strict_types=1);

namespace gift\appli\models;
use \Illuminate\Database\Eloquent\Model as Eloq;

class Box extends Eloq {
      protected $table = 'box';
      protected $primarykey = 'id';
      public $timestamps = false;

      public function user(){
            return $this->belongsTo('User', 'id');
      }
}
