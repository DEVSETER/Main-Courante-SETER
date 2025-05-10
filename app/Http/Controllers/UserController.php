<?php

namespace App\Http\Controllers;

use App\Models\User;
// use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function store(Request $request)
{
    // dd('store');
    // dd("user");
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


    // 3. Rediriger ou retourner une réponse
    return redirect()->route('/dashboard')->with('success', 'Utilisateur ajouté avec succès.');
}


// public function login(Request $request)
// {
//     // dd('login');
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required|string',
//     ]);

//     $user = User::where('email', $request->email)->first();

//     if (!$user || !Hash::check($request->password, $user->password)) {
//         return response()->json(['message' => 'Identifiants invalides'], 401);
//     }

//     dd ($user);
//     return response()->json([
//         'token' => $user->createToken('web-token')->plainTextToken,
//     ]);
// }
// public function create()
// {
//     $roles = \App\Models\Role::all(); // si tu veux afficher les rôles dynamiquement
//     return view('users.create', compact('roles'));
// }

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

public function getUsers()
{
    $users = User::with('role')->get(); // Inclure les rôles si nécessaire
    return view('users.liste-users', compact('users'));
}
public function edit($id)
{
    $user = User::findOrFail($id); // Récupérer l'utilisateur par son ID
    return view('users.edit', compact('user')); // Retourner une vue pour l'édition
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    $user->update($request->all()); // Mettre à jour les données
    return redirect()->route('users.getUsers')->with('success', 'Utilisateur mis à jour avec succès.');
}

public function destroy($id)
{
    $user = User::findOrFail($id); // Récupérer l'utilisateur par son ID
    $user->delete(); // Supprimer l'utilisateur

    return redirect()->route('users.getUsers')->with('success', 'Utilisateur supprimé avec succès.');
}
}

