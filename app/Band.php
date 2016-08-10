<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Band extends Model {
    
    use HasSlugTrait;
    
	protected $table = 'bands';
    
    public function users(){
        return $this->belongsToMany('App\User', 'user_roles', 'band_id', 'user_id');
    }
    
    /*
        function editMember
            $input['oldemail']
            $input['newemail']
    */
    public function editMember($input){
        $user = User::where('email', $input['oldemail'])
            ->whereRaw('id in (select user_id from user_roles where band_id = ?)', array($this->id))
            ->first();
        if ($user){
            $validator = $user->validate(['email' => $input['newemail']]);
            if (count($validator->errors()) == 0){
                if ($user->facebook_user_id){
                    return ['error' => 406, 'message' => 'This user has already linked their facebook account, so their email address cannot be edited.'];
                }else{
                    $user->email = $input['newemail'];
                    $user->save();
                }
                return ['success' => '1'];
            }else{
                return ['error' => 406, 'message' => 'duplicate email'];
            }
        }else{
            return ['error' => 406, 'message' => 'This user was not found in your band'];
        }
    }
    
    public function addMember($email, $role = 'band-member'){
        $user = new User;
        $validator = $user->validate(['email' => $email]);
        if (count($validator->errors()) == 0){
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
