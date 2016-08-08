<?php
namespace App;

trait HasPermissionsTrait{
    public function permissions(){
        return $this->belongsToMany('App\Permission', self::$perimssion_pivot_table, self::$perimssion_pivot_key, 'permission_id');
    }

    public function givePermissionTo(Permission $permission){
        return (! $this->hasPermission($permission)) ? $this->permissions()->save($permission) : 1;
    }
    
    public function revokePermission(Permission $permission){
        return $this->permissions()->detach($permission->id);
    }
    
    //$user->hasPermission('manage-schedule') or $user->hasPermission(Permission $permission)
    public function hasPermission($permission){
        if (is_string($permission)){
            return $this->permissions->contains('slug', $permission);
        }
        
        return $this->permissions->contains('slug', $permission->slug);
    }    
}
