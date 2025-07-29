<?php

namespace App\Http\Controllers;


use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PermissionController extends Controller implements HasMiddleware
{


    public static function middleware(): array
{
    return [
    // new Middleware('permission:Consulter liste privilège', only: ['index']),

        new Middleware('permission:Consulter liste privilège', only: ['index']),
        new Middleware('permission:Modifier privilège', only: ['edit']),
        new Middleware('permission:Créer privilège', only: ['create']),
        new Middleware('permission:Supprimer privilège', only: ['destroy']),

    ];
}
public function index()
{
    // dd('hksbdvnc');
    // $permissions = Permission::orderBy('created_at', 'desc')->Paginate(10);
    // return view ('permission.index', [
    //     'permissions' => $permissions,
    // ]);
      $permissions = Permission::all(); // Récupérer toutes les permissions
    return view('permission.index', compact('permissions')); // Passer les permissions à la vue

}

// public function create()
// {
//     return view('permission.permission-create');
// }

public function create()
{
    // Récupérer les valeurs de l'enum 'type' depuis la base de données
    $types = DB::select("SHOW COLUMNS FROM permissions WHERE Field = 'type'");
    $enumStr = $types[0]->Type; // ex: enum('administration','organisation',...)
    preg_match('/enum\((.*)\)$/', $enumStr, $matches);
    $enum = [];
    foreach (explode(',', $matches[1]) as $value) {
        $enum[] = trim($value, "'");
    }

    return view('permission.permission-create', [
        'types' => $enum
    ]);
}

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|unique:permissions,name|min:3',
        'type' => 'required|string',
    ]);

    if ($validator->passes()) {
        Permission::create([
            'name' => $request->name,
            'type' => $request->type,
            'guard_name' => 'web', // Ajout du guard_name obligatoire pour Spatie
        ]);
        return redirect()->route('permissions.index')->with('success', 'Privilège créé avec succès.');
    } else {
        return redirect()->route('permissions.index')->withInput()->withErrors($validator);
    }
}

//   public function store(Request $request)
//     {

// $validator =Validator::make($request->all(), [
//     'name' => 'required|string|unique:permissions,name|min:3',
//     'type' => 'required|string',
// ]);
//     if ($validator->passes()) {
//     Permission::create([
//         'name' => $request->name,
//         'type' => $request->type,

//     ]);
//         return redirect()->route('permissions.index')->with('success', 'Privilège créé avec succès.');

// }
//     else {
//             return  redirect()->route('permissions.index')->withInput()->withErrors($validator);
// }


// }


// public function edit($id)
// {
//     $permission = Permission::findOrFail($id); // Récupérer le rôle par son ID
//     return view('permission.permission-edit', compact('permission')); // Passer $role et $permissions à la vue


// }

public function edit($id)
{
    $permission = Permission::findOrFail($id);

    // Récupérer les valeurs de l'enum 'type' depuis la base de données
    $types = DB::select("SHOW COLUMNS FROM permissions WHERE Field = 'type'");
    $enumStr = $types[0]->Type;
    preg_match('/enum\((.*)\)$/', $enumStr, $matches);
    $enum = [];
    foreach (explode(',', $matches[1]) as $value) {
        $enum[] = trim($value, "'");
    }

    return view('permission.permission-edit', [
        'permission' => $permission,
        'types' => $enum
    ]);
}


public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|min:3|unique:permissions,name,' . $id,
        'type' => 'nullable|string',
    ]);

    $permission = Permission::findOrFail($id);
    $permission->name = $request->name;
    $permission->type = $request->type;
    $permission->save();

    return redirect()->route('permissions.index')->with('success', 'Permission modifiée avec succès.');
}

public function destroy(Request $request)
{
   $id=$request->id;
    $permission = Permission::findOrFail($id);
    if($permission== null){
        return redirect()->route('permissions.index')->with('error', 'Privilège introuvable.');
    }
    $permission->delete();

    return redirect()->route('permissions.index')->with('success', 'Privilège supprimé avec succès.');
}
}
