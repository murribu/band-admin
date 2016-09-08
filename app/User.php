<?php

namespace App;
use DB;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class User extends Authenticatable
{
    
    use HasSlugTrait;
    
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
    
    private static $rules = [
        'email' => 'required|unique:users,email'
    ];
    
    public function validate($data, $user_id = 0){
        $rules = self::$rules;
        $rules['email'] .= ','.$user_id;
        $v = Validator::make($data, $rules);
        return $v;
    }
    
    public function band(){
        return $this->bands()[0];
    }
    
    public function bands(){
        return Band::join('user_roles', 'bands.id', '=', 'user_roles.band_id')
            ->where('user_roles.user_id', $this->id)
            ->select('bands.*')
            ->orderBy('user_roles.active', 'desc')
            ->get();
    }
    
    public function permissions($band){
        if (is_string($band)){
            $band = Band::where('slug', $band)->first();
        }
        return Permission::whereRaw('id in (select permission_id from user_permissions where user_id = ? and band_id = ?)', array($this->id, $band->id))->get();
    }
    
    public function givePermissionTo(Permission $permission, Band $band){
        $up = UserPermission::firstOrCreate([
            'permission_id' => $permission->id,
            'band_id'       => $band->id,
            'user_id'       => $this->id
        ]);
        return 1;
    }
    
    public function revokePermission(Permission $permission, Band $band){
        $up = UserPermission::where('permission_id', $permission->id)
            ->where('band_id', $band->id)
            ->where('user_id', $this->id)
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
        $ret = UserPermission::where('band_id', $band->id)->where('permission_id', $permission->id)->where('user_id', $this->id)->count() > 0;
        return $ret;
    }    
    
    public static function create_from_facebook_login($login_user){
        
        $facebook_user = FacebookUser::where('facebook_id', $login_user->id)->first();
        if ($facebook_user){
            $user = $facebook_user->user;
        }else{
            $facebook_user = new FacebookUser;
            $facebook_user->name = $login_user->name;
            $facebook_user->email = $login_user->email;
            $facebook_user->avatar = $login_user->avatar;
            $facebook_user->facebook_id = $login_user->id;
            $facebook_user->save();
            $user = User::where('email', $login_user->email)->first();
            if (!$user){
                $user = new User;
                $user->email = $login_user->email;
            }
            $user->name = $login_user->name;
            $user->facebook_user_id = $facebook_user->id;
        }
        
        $user->last_login = date("Y-m-d H:i:s");
        $user->save();
        
        if (count($user->roles) == 0){
            //Create a band for this user and make them the admin of it
            $role = Role::where('slug', 'band-administrator')->first();
            $band = new Band;
            $band->name = $user->name. "'s Band";
            $band->slug = Band::findSlug($band->name);
            $band->save();
            $user->assignRole($role, $band);
        }
        
        return $user;
    }
    
    public function facebook_user(){
        return $this->belongsTo('App\FacebookUser');
    }
    
    public function user_roles(){
        return $this->hasMany('App\UserRole');
    }
    
    public function roles(){
        // return $this->belongsToMany('App\Role');
        return $this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
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
            
            $ur = UserRole::firstOrCreate(['user_id' => $this->id, 'band_id' => $band->id, 'role_id' => $role->id]);
            foreach($role->permissions() as $permission){
                $this->givePermissionTo($permission, $band);
            }
        }
    }
}
