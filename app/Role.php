<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    
    use HasPermissionsTrait;
    public static $perimssion_pivot_table = 'role_permissions';
    public static $perimssion_pivot_key = 'role_id';
	protected $table = 'roles';
}
