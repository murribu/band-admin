<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model {
	protected $table = 'role_permissions';
    
    public function permission(){
        return $this->belongsTo('App\Permission');
    }
    
    public function role(){
        return $this->belongsTo('App\Role');
    }
}
