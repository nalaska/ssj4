<?php

namespace App\Http\Controllers;

use App\Enums\Belt;
use App\Models\Role as ModelsRole;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $beltOrder = [
            'blanche', 'grise', 'jaune', 'orange', 'verte', 
            'bleu', 'violette', 'marron', 'noire', 
            'rouge_noire', 'rouge_blanche', 'rouge'
        ];

        $users = User::orderByRaw(
            "FIELD(belt, '" . implode("', '", $beltOrder) . "')"
        )->get();

        return Inertia::render('Users/Index', ['users' => $users]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'belt' => 'required|in:' . implode(',', Belt::getValues()),
            'phone' => 'required|string|max:20',
            'year_of_registration' => 'required|integer',
            'status' => 'required|string|max:255',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'picture' => 'nullable|image|max:2048',
            'date_of_birth' => 'required|date'
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'belt' => $request->belt,
            'phone' => $request->phone,
            'year_of_registration' => $request->year_of_registration,
            'status' => $request->status,
            'picture' => $request->file('picture') ? $request->file('picture')->store('pictures', 'public') : null,
            'date_of_birth' => $request->date_of_birth
        ]);
    
        $roleIds = ModelsRole::whereIn('name', $request->roles)->pluck('id')->toArray();
    
        $user->roles()->sync($roleIds);
    
        return redirect()->route('users.index');
    }

    public function edit(User $user): Response
    {
        $roles = $user->roles()->pluck('name');
        return Inertia::render('Users/Edit', ['user' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        try {
            $deleted = $user->delete();
            if ($deleted) {
                return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
            } else {
                return redirect()->route('users.index')->with('error', 'Échec de la suppression de l\'utilisateur.');
            }
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Erreur lors de la suppression de l\'utilisateur : ' . $e->getMessage());
        }
        return redirect()->route('users.index');
    }
}