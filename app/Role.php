<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    
	protected $table = 'roles';
    
    public function permissions(){
        return Permission::whereRaw('id in (select permission_id from role_permissions where role_id = ?)', array($this->id))->get();
    }
    
    public function givePermissionTo(Permission $permission){
        RolePermission::firstOrCreate([
            'permission_id' => $permission->id,
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
