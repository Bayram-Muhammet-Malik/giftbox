<?php
declare(strict_types=1);

namespace gift\appli\models;

use \Illuminate\Database\Eloquent as Eloq;
class User extends Eloq\Model {
    protected $table = 'user';
    protected $primarykey = 'id';
    public $timestamps = false;
    public $keyType = 'string';

    protected $fillable = ['id', 'user_id', 'password'];

    public function box(){
        return $this->hasMany('Box','id_user', 'id');
    }
}