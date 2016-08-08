<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    
    use HasPermissionsTrait, HasSlugTrait;
    public static $perimssion_pivot_table = 'user_permissions';
    public static $perimssion_pivot_key = 'user_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function facebook_user(){
        return $this->belongsTo('App\FacebookUser');
    }
    
    public function roles(){
        // return $this->belongsToMany('App\Role');
        return $this->belongsToMany('App\Permission', 'user_permissions', 'user_id', 'permission_id');
    }
    
    public function hasRole($role, $band){
        if (is_string($role)){
            $role = Role::where('slug', $role)->first();
        }
        if (is_string($band)){
            $band = Band::where('slug', $band)->first();
        }
        
        return $role && $band && UserRole::where('role_id', $role->id)->where('band_id', $band->id)->count() > 0;
    }
    
    public function assignRole($role, $band){
        if (is_string($band)){
            $band = Band::where('slug', $band)->first();
        }
        if (is_string($role)){
            $role = Role::where('slug', $role)->first();
        }
        if ($band && $role){
            
            UserRole::firstOrCreate(['user_id' => $this->id, 'band_id' => $band->id, 'role_id' => $role->id]);
            
            foreach($role->permissions as $permission){
                $this->givePermissionTo($permission);
            }
        }
    }
}
