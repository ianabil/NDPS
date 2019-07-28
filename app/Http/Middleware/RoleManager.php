<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $roles = is_array($role)
            ? $role
            : explode('|', $role);
            
        if(!Auth::check())
            return route('login');
        else if (Auth::user()->hasAnyRole($roles)) {
            return $next($request)->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
        }
        else
            abort(403,"It seems you do not possess permission to access this page");   
    }
}
