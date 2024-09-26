<?php

namespace App\Http\Controllers;

use App\Enums\Belt;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'status' => 'required|string|max:255',
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

        //TODO: régler pb image JPG et l'update image qui casse tout
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'belt' => 'required|in:' . implode(',', Belt::getValues()),
            'phone' => 'required|string|regex:/^0[1-9][0-9]{8}$/',
            'year_of_registration' => 'required|integer|digits:4|lte:' . date('Y'),
            'status' => 'required|string|max:255',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'picture' => 'nullable|max:2048', 
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

    public function generateToken(Request $request)
    {
        $user = $request->user();
        $roles = $user->roles()->pluck('name')->toArray();
        $token = $user->createToken('auth_token', $roles)->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    public function revokeToken(Request $request, $tokenId)
    {
        $user = $request->user();
        $user->tokens()->where('id', $tokenId)->delete();
        return response()->json(['message' => 'Jeton révoqué'], 200);
    }

    public function listTokens(Request $request)
    {
        $user = $request->user();
        $tokens = $user->tokens()->get();
        return response()->json(['tokens' => $tokens], 200);
    }
}