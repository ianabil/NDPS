<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class High_Court
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && Auth::user()->user_type=='high_court')
            return $next($request);
        else if(Auth::check() && Auth::user()->user_type=='stakeholder')
            return redirect('entry_form');
    }
}
