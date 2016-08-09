<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BandUsers extends Model {
	protected $table = 'band_users';
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function band(){
        return $this->belongsTo('App\Band');
    }
}
