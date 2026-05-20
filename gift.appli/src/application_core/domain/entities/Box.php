<?php
declare(strict_types=1);

namespace gift\core\domain\entities;
use \Illuminate\Database\Eloquent as Eloq;

class Box extends Eloq\Model {
      protected $table = 'box';
      protected $primarykey = 'id';
      public $timestamps = false;

      public function user() {
            return $this->belongsTo(User::class, 'createur_id');
      }

      public function prestation() {
            return $this->belongsToMany(Prestation::class, 'box2presta', 'box_id', 'presta_id');
      }
}
