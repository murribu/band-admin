<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite;

use App\FacebookToken;
use App\FacebookUser;
use App\User;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    
    public function redirectToProvider(){
        return Socialite::driver('facebook')->redirect();
    }
    
    public function handleProviderCallback(){
        $login_user = Socialite::driver('facebook')->user();

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
            $user = new User;
            $user->facebook_user_id = $facebook_user->id;
        }
        $user->last_login = date("Y-m-d H:i:s");
        $user->save();

        Auth::loginUsingId($user->id);
        return view('killwindow');
    }
    
    public function getMe(){
        $user = Auth::user();
        $return = [
            'logged_in' => 0,
        ];
        if ($user && $user->facebook_user){
                
            $return['email'] = $user->facebook_user->email;
            $return['name'] = $user->facebook_user->name;
            $return['avatar'] = $user->facebook_user->avatar;
            $return['logged_in'] = 1;
        }
        
        return $return;
    }
    
    public function loginMe(){
        $user = Auth::user();
        if ($user){
            echo('logged in');
        }else{
            echo('logging in...');
        }
        $user = Auth::loginUsingId(1);
        return $user;
    }
}
