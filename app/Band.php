<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Band extends Model {
    
    use HasSlugTrait;
    
	protected $table = 'bands';
    
    public function users(){
        return $this->belongsToMany('App\User', 'user_roles', 'band_id', 'user_id');
    }
    
    public function addMember($email, $role = 'band-member'){
        if (User::validate(['email' => $email])){
            $user = User::where('email', $email)->first();
            if (is_string($role)){
                $role = Role::where('slug', $role)->first();
            }
            if (!$role){
                return ['error' => 500, 'message' => 'Bad Role input'];
            }
            if (!$user){
                $user = new User;
                $user->email = $email;
                $user->save();
            }
            $user->assignRole($role, $this);
            
            return ['success' => '1'];
        }else{
            return ['error' => 406, 'message' => 'duplicate email'];
        }
    }
}
