<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Stakeholder
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
        if(Auth::check() && Auth::user()->user_type=='stakeholder')
            return $next($request);
        else if(Auth::check() && Auth::user()->user_type=='high_court')
            return redirect('/dashboard');
        else if(Auth::check() && Auth::user()->user_type=='magistrate')
            return redirect('magistrate_entry_form');

    }
}
