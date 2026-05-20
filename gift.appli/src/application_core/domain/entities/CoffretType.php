<?php
declare(strict_types=1);

namespace gift\core\domain\entities;

use \Illuminate\Database\Eloquent as Eloq;

class CoffretType extends Eloq\Model {
    protected $table = 'coffret_type';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function prestations() {
        return $this->belongsToMany(Prestation::class, 'coffret2presta', 'coffret_id', 'presta_id');
    }

    public function theme() {
        return $this->belongsTo(Theme::class, 'theme_id');
    }
}
