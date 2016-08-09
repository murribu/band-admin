<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model {
	protected $table = 'user_permissions';
    
    protected $fillable = ['user_id', 'permission_id', 'band_id'];
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function permission(){
        return $this->belongsTo('App\Permission');
    }
}
