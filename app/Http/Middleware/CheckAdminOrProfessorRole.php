<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminOrProfessorRole
{
    public function handle(Request $request, Closure $next)
    {
        if (
            !Auth::check() || 
            (!Auth::user()->roles->contains('name', 'administrateur') && 
            !Auth::user()->roles->contains('name', 'professeur'))
        ){
            return redirect()->route('users.index')->with('error', 'Accès refusé.');
        }

        return $next($request);
    }
}