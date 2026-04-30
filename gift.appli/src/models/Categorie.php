<?php
declare(strict_types=1);

namespace gift\appli\models;

use \Illuminate\Database\Eloquent as Eloq;

class Categorie extends Eloq
{
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    public $timestamps = false;

// libbélés et description en + dans la db
    protected $fillable = [
        'libelle',
        'description'
    ];

    public function prestations()
    {
        return $this->hasMany('Prestation', 'cat_id');
    }

}
