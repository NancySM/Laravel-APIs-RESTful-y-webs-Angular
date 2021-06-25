<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class car extends Model
{
    protected $table='cars';
    //Relación
    public function user(){
    	return $this-> belongsTo('App\User','user_id');
    }
}
