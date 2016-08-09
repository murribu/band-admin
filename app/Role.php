<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    
    use HasPermissionsTrait;
    public static $permission_pivot_table = 'role_permissions';
    public static $permission_pivot_key = 'role_id';
	protected $table = 'roles';
    
    public function permissions(){
        return Permission::whereRaw('id in (select permission_id from role_permissions where role_id = ?)', array($this->id))->get();
    }
    
    public function givePermissionTo(Permission $permission, Band $band){
        UserPermission::firstOrCreate([
            'permission_id' => $permission->id,
            'band_id'       => $band->id,
            'role_id'       => $this->id
        ]);
        return 1;
    }
    
    public function revokePermission(Permission $permission, Band $band){
        $up = UserPermission::where('permission_id', $permission->id)
            ->where('band_id', $band->id)
            ->where('role_id', $this->id)
            ->first();
        if ($up){
            $up->delete();
        }
        return 1;
    }
    
    //$user->hasPermission('manage-schedule') or $user->hasPermission(Permission $permission)
    public function hasPermission($permission, $band){
        if (is_string($permission)){
            $permission = Permission::where('slug', $permission)->first();
        }
        if (is_string($band)){
            $band = Band::where('slug', $band)->first();
        }
        
        return RolePermission::where('band_id', $band->id)->where('permission_id', $permission->id)->where('role_id', $this->id)->count() > 0;
    }    
}
