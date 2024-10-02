<?php

namespace App\Http\Controllers;

use App\Enums\Belt;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {}

    public function index(): Response
    {
        return Inertia::render('Users/Index', ['users' => $this->userService->getUsersByBeltOrder()]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request): RedirectResponse
    {
         //TODO: régler pb image JPG 
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'belt' => 'required|in:' . implode(',', Belt::getValues()),
            'phone' => 'required|string|regex:/^0[1-9][0-9]{8}$/',
            'year_of_registration' => 'required|integer|digits:4|lte:' . date('Y'),
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'picture' => 'nullable|image|max:2048',
            'date_of_birth' => 'required|date',
        ]);
    
        $this->userService->createUser($request);
    
        return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès.');
    }

    public function edit(User $user): Response
    {
        $roles = $user->roles()->pluck('name');
        return Inertia::render('Users/Edit', ['user' => $user, 'roles' => $roles]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Utilisateur non trouvé.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'belt' => 'required|in:' . implode(',', Belt::getValues()),
            'phone' => 'required|string|regex:/^0[1-9][0-9]{8}$/',
            'year_of_registration' => 'required|integer|digits:4|lte:' . date('Y'),
            'status' => 'required|string|max:255',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'date_of_birth' => 'required|date',
        ]);
    
        $this->userService->updateUser($request, $user);
        $userName = $user->name;
    
        return redirect()->route('users.index')->with('success', "Utilisateur $userName modifié.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->userService->deleteUser($user);
        return redirect()->route('users.index');
    }

    public function attendance(User $user): Response
    {
        return Inertia::render('Users/AttendanceSheet', [
            'user' => $user
        ]);
    }

    public function updateAttendance(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'attendance' => 'required|json',
        ]);

        $user->attendance = $request->attendance;
        $user->save();
        $userName = $user->name;

        return redirect()->route('users.index')->with('success', "Feuille de présence modifiée pour $userName");
    }
    
    public function editPicture(User $user): Response
    {
        return Inertia::render('Users/EditPicture', ['user' => $user]);
    }

    public function updatePicture(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'picture' => 'required|image|max:2048',
        ]);

        if ($request->file('picture')) {
            $user->picture ? Storage::disk('public')->delete($user->picture) : null;
            $user->picture = $request->file('picture')->store('pictures', 'public');
            $user->save();
        }

        return redirect()->route('users.edit', $user)->with('success', 'Photo mise à jour avec succès.');
    }
}