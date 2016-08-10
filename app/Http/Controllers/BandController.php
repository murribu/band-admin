<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Response;

use App\Band;
use App\Permission;
use App\User;

class BandController extends Controller {
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getBand(){
        $user = Auth::user();
        $band = Band::with('users', 'users.roles')->find($user->user_roles[0]->band_id);
        
        return $band;
    }
    
    public function getMember($email){
        $user = Auth::user();
        return User::whereRaw('id in (select user_id from user_roles where band_id = ? )', array($user->user_roles[0]->band_id))
            ->where('email', $email)
            ->first();
    }
    
    public function postMember(){
        $user = Auth::user();
        $band = $user->band(); // :(
        $test = $user->hasPermission('manage-band-users', $band);
        if ($user->hasPermission('manage-band-users', $band) || $user->can('manage-all-users', $band)){
            if (Input::has('oldemail')){
                //update
                $ret = $band->editMember(Input::all());
                if (isset($ret['error'])){
                    return Response::json($ret, $ret['error']);
                }else{
                    return $ret;
                }
            }else{
                $ret = $band->addMember(Input::get('email'));
                if (isset($ret['error'])){
                    return Response::json($ret, $ret['error']);
                }else{
                    return $ret;
                }
            }
        }else{
            return abort(403);
        }
    }
}