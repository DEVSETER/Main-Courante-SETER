<?php
// app/Http/Controllers/RoleController.php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
       $roles = Role::all();
    //    dd($roles);

    return view('role', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|unique:roles,libelle',
        ]);

        Role::create([
            'libelle' => $request->libelle,
        ]);

        return redirect()->back()->with('success', 'Rôle créé avec succès.');
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'libelle' => 'required|string|unique:roles,libelle,' . $role->id,
        ]);

        $role->update([
            'libelle' => $request->libelle,
        ]);

        return redirect()->back()->with('success', 'Rôle mis à jour.');
    }
public function edit($id)
{
    $role = Role::findOrFail($id); // Récupérer le rôle par son ID
    $permissions = Permission::all(); // Récupérer toutes les permissions
    return view('role', compact('role', 'permissions')); // Passer $role et $permissions à la vue
}


    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->back()->with('success', 'Rôle supprimé.');
    }

public function updatePermissions(Request $request, $id)
{
    $role = Role::findOrFail($id);

    // Synchroniser les permissions
    $role->permissions()->sync($request->permissions);

    return redirect()->back()->with('success', 'Permissions mises à jour avec succès.');
}
}

