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
        $input = $request->all();
        array_walk_recursive($input, function(&$input) {
            $input = strip_tags($input);
        });
        $request->merge($input);
        
        $roles = is_array($role)
            ? $role
            : explode('|', $role);
        
        $user_role = Auth::user()->user_type;

        if(!Auth::check())
            return route('login');
        
        foreach($roles as $role){
            if($role == $user_role)
                return $next($request)
                ->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma','no-cache')
                ->header('Expires','-1')
                ->header('Strict-Transport-Security','max-age=31536000; includeSubDomains; preload')
                ->header('X-XSS-Protection','1;mode=block')
                ->header('X-Frame-Options','SAMEORIGIN')
                ->header('Content-Security-Policy',"script-src 'self' 'unsafe-inline' 'unsafe-eval'")
                ->header('X-Content-Type-Options','nosniff');               
        }        
        
        abort(403,"It seems you do not possess permission to access this page");   
    }
}
