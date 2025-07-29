<?php
// app/Http/Controllers/RoleController.php
namespace App\Http\Controllers;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Consulter liste rôles', only: ['index']),
            new Middleware('permission:Modifier rôle', only: ['edit']),
            new Middleware('permission:Créer rôle', only: ['create']),
            new Middleware('permission:Supprimer rôle', only: ['destroy']),
        ];
    }



public function index()
{

    $roles = Role::with('permissions')->get(); // Charger les permissions associées à chaque rôle
    return view('role.index', compact('roles'));


}




public function create()
{
    
    $permissions = Permission::all()->groupBy('type');
    return view('role.role-create', compact('permissions'));
}



public function store(Request $request)
{
    // Validation des données
    $validator = Validator::make($request->all(), [
        'name' => 'required|unique:roles|min:3',
        'permissions' => 'nullable|array', // Les permissions peuvent être nulles ou un tableau
        'permissions.*' => 'exists:permissions,id', // Chaque permission doit exister dans la table permissions
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator);
    }

    // Créer le rôle
    $role = Role::create([
        'name' => $request->name,
        'guard_name' => 'web'
    ]);

    // Associer les permissions au rôle (si des permissions sont sélectionnées)
    if ($request->has('permissions')) {
        $role->permissions()->sync($request->permissions);
        // dd($role->permissions);
    }

    return redirect()->route('roles.index')->with('success', 'Rôle créé avec succès.');
}





// public function edit($id)
// {
//     $role = Role::with('permissions')->findOrFail($id); // Charger le rôle avec ses permissions
//     $permissions = Permission::all(); // Charger toutes les permissions disponibles
//     return view('role.role-edit', compact('role', 'permissions')); // Passer $role et $permissions à la vue
// }

public function edit($id)
{
    $role = Role::with('permissions')->findOrFail($id);

    $permissions = Permission::all()->groupBy('type');
    return view('role.role-edit', compact('role', 'permissions'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|min:3|unique:roles,name,' . $id,
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    $role = Role::findOrFail($id);
    $role->update(['name' => $request->name]);

    // Mettre à jour les permissions associées
    $role->permissions()->sync($request->permissions);

    return redirect()->route('roles.index')->with('success', 'Rôle mis à jour avec succès.');
}
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->back()->with('success', 'Rôle supprimé.');
    }

// public function updatePermissions(Request $request, $id)
// {
//     $role = Role::findOrFail($id);

//     // Synchroniser les permissions
//     $role->permissions()->sync($request->permissions);

//     return redirect()->back()->with('success', 'Permissions mises à jour avec succès.');
// }
// public function updatePermissions(Request $request, $roleId)
// {
//     $role = Role::findOrFail($roleId);

//     // Synchroniser les permissions sélectionnées
//     $role->permissions()->sync($request->input('permissions', []));

//     return redirect()->back()->with('success', 'Permissions mises à jour avec succès pour le rôle : ' . $role->libelle);
// }

// public function grantAllPermissions($roleId)
// {
//     // Récupérer le rôle par son ID
//     $role = Role::findOrFail($roleId);

//     // Récupérer toutes les permissions disponibles
//     $permissions = Permission::all();

//     // Associer toutes les permissions au rôle
//     $role->permissions()->sync($permissions->pluck('id'));

//     return redirect()->back()->with('success', 'Toutes les permissions ont été accordées au rôle.');
// }

}

