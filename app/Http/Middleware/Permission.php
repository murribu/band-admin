<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Response;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions_str)
    {
        $user = Auth::user();
        $band = $user->band();
        $permissions = explode("|", $permissions_str);

        foreach($permissions as $permission){
            if ($user && $user->hasPermission($permission, $band)){
                return $next($request);
            }
        }
        
        return Response::json('You do not have access to perform this function', 403);
    }
}
