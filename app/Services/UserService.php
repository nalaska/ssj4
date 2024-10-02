<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role as ModelsRole;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function createUser(Request $request): User
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'belt' => $request->belt,
            'phone' => $request->phone,
            'year_of_registration' => $request->year_of_registration,
            'status' => 'actif',
            'picture' => $request->file('picture') ? $request->file('picture')->store('pictures', 'public') : null,
            'date_of_birth' => $request->date_of_birth,
            'attendance' => $request->attendance
        ]);

        $this->syncRoles($user, $request->roles);

        return $user;
    }

    public function updateUser(Request $request, User $user): User
    {
        $user->update($request->only(['name', 'email', 'belt', 'phone', 'year_of_registration', 'status', 'date_of_birth', 'attendance']));
        $this->syncRoles($user, $request->roles);

        return $user;
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    private function syncRoles(User $user, array $roles): void
    {
        $roleIds = ModelsRole::whereIn('name', $roles)->pluck('id')->toArray();
        $user->roles()->sync($roleIds);
    }

    public function getUsersByBeltOrder(): Collection
    {
        $beltOrder = [
            'blanche', 'grise', 'jaune', 'orange', 'verte', 
            'bleu', 'violette', 'marron', 'noire', 
            'rouge_noire', 'rouge_blanche', 'rouge'
        ];

        return User::orderByRaw(
            "FIELD(belt, '" . implode("', '", $beltOrder) . "')"
        )->get();
    }

}