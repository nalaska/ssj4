<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckAdminOrProfessorRole;
use App\Http\Middleware\CheckAdminRole;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/users');
    }
    return redirect('/login');
});

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

Route::get('/auth/callback', function () {
    return Inertia::render('Auth/AuthCallback');
})->name('auth.callback');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('auth/logout', [GoogleController::class, 'logout'])->name('logout.google');

Route::middleware(['auth'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::middleware([CheckAdminRole::class])->group(function () {
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware([CheckAdminOrProfessorRole::class])->group(function () {
        Route::get('/users/{user}/attendance', [UserController::class, 'attendance'])->name('users.attendance');
        Route::post('/users/{user}/attendance', [UserController::class, 'updateAttendance'])->name('users.updateAttendance');
    });
});

require __DIR__.'/auth.php';
