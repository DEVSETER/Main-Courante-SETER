<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Routing\Controllers\HasMiddleware;
// use Illuminate\Routing\Controllers\Middleware;


class UserController extends Controller
{
//  public function middleware(): array {
//     return [
//         'permission:view user' => ['only' => ['index']],
//         'permission:edit user' => ['only' => ['edit']],
//     ];
//  }

    public function store(Request $request)
{


    // 1. Valider les données
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'telephone' => 'nullable|string',
        'role_id' => 'required|exists:roles,id',
        'direction' => 'nullable|string',
        'fonction' => 'nullable|string',
    ]);

    // 2. Créer l’utilisateur
    // dd($request);

    $user = User::create([
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'telephone' => $request->telephone,
        'role_id' => $request->role_id,
        'direction' => $request->direction,
        'fonction' => $request->fonction,
    ]);
// Récupérer le rôle de l'utilisateur

    // 3. Rediriger ou retourner une réponse
    return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès.');
}


public function create()
{
    $roles = Role::all(); // Récupérer tous les rôles
    return view('users.user-create', compact('roles')); // Passer les rôles à la vue
}


public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    return response()->json(['message' => 'Identifiants invalides.'], 401);
}

public function index()
{
    $users = User::with('role')->get(); // Inclure les rôles si nécessaire
    return view('users.index', compact('users'));
}
public function edit($id)
{
    $user = User::findOrFail($id); // Récupérer l'utilisateur par son ID
    $roles = Role::all(); // Récupérer tous les rôles
    return view('users.edit', compact('user','roles')); // Retourner une vue pour l'édition
}

// public function update(Request $request, $id)
// {
//     $user = User::findOrFail($id);
//     $user->update($request->all()); // Mettre à jour les données
//     return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
// }

public function update(Request $request, $id)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $id,
        'telephone' => 'nullable|string|max:20',
        'fonction' => 'nullable|string|max:255',
        'direction' => 'nullable|string|max:255',
        'role_id' => 'nullable|exists:roles,id',
        'password' => 'nullable|string|min:8', // Le mot de passe est optionnel
    ]);

    $user = User::findOrFail($id);

    // Mettre à jour les champs sauf le mot de passe
    $user->update($request->except('password'));

    // Mettre à jour le mot de passe uniquement s'il est fourni
    if ($request->filled('password')) {
        $user->update([
            'password' => bcrypt($request->password),
        ]);
    }

    return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
}
public function destroy($id)
{
    $user = User::findOrFail($id); // Récupérer l'utilisateur par son ID
    $user->delete(); // Supprimer l'utilisateur

    return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
}
}

