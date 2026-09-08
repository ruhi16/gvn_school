<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if(!Auth::check()){
            return redirect('login');
        }

        $user = Auth::user();

        foreach($roles as $role){
            if($user->hasRole($role)){
                return $next($request);        
            }
        }

        abort(403, 'You do not have permission to access this page!');

        
    }
}
