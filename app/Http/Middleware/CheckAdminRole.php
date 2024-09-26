<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->roles->contains('name', 'administrateur')) {
            return redirect()->route('users.index')->with('error', 'Accès non autorisé');
        }
    
        return $next($request);
    }
}