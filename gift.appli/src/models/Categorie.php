<?php
declare(strict_types=1);

namespace gift\appli\models;

use \Illuminate\Database\Eloquent as Eloq;

class Categorie extends Eloq\Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'libelle',
        'description'
    ];

    public function prestations()
    {
        return $this->hasMany('Prestation', 'cat_id');
    }

}
