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
    
    public function events(){
        return $this->hasMany('App\Event');
    }
    
    public function edit($input){
        if (isset($input['name'])){
            $this->name = $input['name'];
            $this->slug = Band::findSlug($input['name']);
        }
        $this->save();
        
        return Band::where('id', $this->id)->with('users','users.roles')->first();
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
            $validator = $user->validate(['email' => $input['newemail']], $user->id);
            if (count($validator->errors()) == 0){
                if ($user->facebook_user_id){
                    return ['error' => 406, 'message' => 'This user has already linked their facebook account, so their info cannot be edited.'];
                }else{
                    $user->email = $input['newemail'];
                    if (isset($input['name'])){
                        $user->name = $input['name'];
                    }
                    $user->save();
                }
                return ['success' => '1'];
            }else{
                $msg = '';
                foreach($validator->errors()->all() as $m){
                    $msg .= $m.',';
                }
                $msg = substr($msg,0,-1);
                return ['error' => 406, 'message' => $msg];
            }
        }else{
            return ['error' => 406, 'message' => 'This user was not found in your band'];
        }
    }
    
    public function editEvent($input){
        $event = Event::where('slug', $input['slug'])
            ->where('band_id', $this->id)
            ->first();
        if ($event){
            $event->update($input);
            return ['success' => '1'];
        }else{
            return ['error' => 406, 'message' => 'This event was not found for your band'];
        }
    }
    
    public function addMember($input, $role = 'band-member'){
        $user = new User;
        $email = $input['email'];
        $name = $input['name'];
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
                $user->name = $name;
                $user->save();
            }
            $user->assignRole($role, $this);
            
            return ['success' => '1'];
        }else{
            $msg = '';
            foreach($validator->errors()->all() as $m){
                $msg .= $m.',';
            }
            $msg = substr($msg,0,-1);
            return ['error' => 406, 'message' => $msg];
        }
    }
    
    public function createEvent($input){
        $input['band_id'] = $this->id;
        return Event::create_from_input($input);
    }
}
