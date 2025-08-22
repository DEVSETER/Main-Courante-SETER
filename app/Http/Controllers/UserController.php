<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entite;

use App\Mail\DemandeMail;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class UserController extends Controller  implements HasMiddleware
{

  public static function middleware(): array
    {
        return [
            new Middleware('permission:Consulter liste utilisateurs', only: ['index']),
            new Middleware('permission:Modifier utilisateur', only: ['edit']),
            new Middleware('permission:Créer utilisateur', only: ['create']),
            new Middleware('permission:Supprimer utilisateur', only: ['destroy']),
        ];
    }
    public function index()

    {
        //   dd([
        //     'user_id' => auth()->id(),
        //     'roles' => auth()->user()->getRoleNames(),
        //     'permissions' => auth()->user()->getAllPermissions()->pluck('name'),
        //     'can_permission' => auth()->user()->can('Consulter liste privilège'),
        // ]);
        $roles = Role::all();
        $entites = Entite::all();
        $users = User::with('roles', 'entite')->get();
        return view('users.index', compact('users', 'roles', 'entites'));
        // $users = User::with('role')->get(); // Inclure les rôles si nécessaire
        // return view('users.index', compact('users'));
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'matricule' => 'required|string|max:255|unique:users,matricule',
        'telephone' => 'nullable|string',
        'direction' => 'required|string',
        'entite_id' => 'nullable|integer',
        'fonction' => 'required|string',
        'role_id' => 'required|exists:roles,id',
    ]);

    $employe = $this->getEmployeByMatricule($validated['matricule']);
    if (!$employe) {
        return back()->withErrors(['matricule' => 'Matricule introuvable dans l\'annuaire.']);
    }

    // ✅ CORRECTION : Ajouter role_id dans User::create()
    $user = User::create([
        'nom' => $employe['nom'] ?? '',
        'prenom' => $employe['prenom'] ?? '',
        'email' => $employe['email'] ?? '',
        'matricule' => $employe['matricule'],
        'telephone' => $employe['telephone'] ?? null,
        'fonction' => $employe['fonction'] ?? null,
        'direction' => $employe['direction'] ?? null,
        'entite_id' => $validated['entite_id'],
        'role_id' => $validated['role_id'], // ✅ AJOUTÉ : role_id manquant
    ]);

    // ✅ Assigner le rôle via Spatie (si vous utilisez les deux systèmes)
    $role = Role::find($validated['role_id']);
    if ($role) {
        $user->assignRole($role->name);
    }



    return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès.');
}
public function create()

{
    // $matricule = $request->query('matricule');
    // $jsonPath = base_path('database/db.json');
    // $json = file_get_contents($jsonPath);
    // $employes = json_decode($json, true);
    // dd($employes);
    $roles = Role::all(); // Récupérer tous les rôles
    $entites = Entite::all(); // Récupérer toutes les entités
    return view('users.user-create', compact('roles', 'entites')); // Passer les rôles et les entités à la vue
}

private function getEmployeByMatricule($matricule)
{
    $jsonPath = base_path('database/db.json');
    $json = file_get_contents($jsonPath);
    $employes = json_decode($json, true);

    foreach ($employes as $employe) {
        if (isset($employe['matricule']) && strval($employe['matricule']) === strval($matricule)) {
            return $employe;
        }
    }
    return null;
}
public function getEmploye(Request $request)
{
    $matricule = $request->query('matricule');
    $jsonPath = base_path('database/db.json');
    $json = file_get_contents($jsonPath);
    $employes = json_decode($json, true);

    foreach ($employes as $employe) {
        if (isset($employe['matricule']) && strval($employe['matricule']) === strval($matricule)) {
            return response()->json($employe);
        }
    }
    return response()->json(['error' => 'Employé introuvable'], 404);
}

// public function login(Request $request)
// {
//     $credentials = $request->only('email', 'password');

//     if (Auth::attempt($credentials)) {
//         $user = Auth::user();
//         $token = $user->createToken('authToken')->plainTextToken;

//         // Mail::to($user->email)->send(new DemandeMail($user));
//         return response()->json([
//             'access_token' => $token,
//             'user' => $user,
//         ]);

//     }

//     return response()->json(['message' => 'Identifiants invalides.'], 401);

// }



public function edit($id)
{
    $user = User::findOrFail($id);
    $roles = Role::all();
    $entites = Entite::all(); // Récupérer toutes les entités
    $userEntite = $user->entite_id ? [$user->entite_id] : []; // Récupérer l'entité de l'utilisateur
    $userRole = $user->roles->pluck('id')->toArray(); // Pour sélection multiple

    return view('users.edit', compact('user', 'roles', 'userRole', 'entites', 'userEntite'));
}


public function update(Request $request, $id)
{

    $user = User::findOrFail($id);
    $request->validate([
        'matricule' => 'required|string|max:255|unique:users,matricule,' .$user->id,
        'entite_id' => 'nullable|integer|exists:entites,id',
        'role_id' => 'required|exists:roles,id',
    ]);



        $roleId = $request->input('role_id');
        $roleNames = \Spatie\Permission\Models\Role::whereIn('id', [$roleId])->pluck('name')->toArray();
        $user->syncRoles($roleNames);

    $data = $request->only(['matricule', 'entite_id']);



    $user->update($data);

        // Mail::to($user->email)->send(new DemandeMail($user));
        // Mail::to($user->email)->queue(new DemandeMail($user));
    return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
}
public function destroy($id)
{
    $user = User::findOrFail($id); // Récupérer l'utilisateur par son ID
    $user->delete(); // Supprimer l'utilisateur

    return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
}

 public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }
}

