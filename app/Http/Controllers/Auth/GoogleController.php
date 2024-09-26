<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            $message = $e->getMessage();
            return redirect()->route('login')->with('error', "Erreur lors de l'authentification Google: $message");
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            Auth::login($user);
            $roles = $user->roles()->pluck('name')->toArray();
            cache()->put('user_roles_' . $user->id, $roles, 3600);
            return redirect()->route('users.index');
        }

        return redirect()->route('login')->with('error', 'Utilisateur introuvable');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            cache()->flush();
        }   

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
