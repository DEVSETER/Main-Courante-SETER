<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PermissionController extends Controller
{

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

public function create()
{
    return view('permission.permission-create');
}


  public function store(Request $request)
    {

$validator =Validator::make($request->all(), [
    'name' => 'required|string|unique:permissions,name|min:3',
]);
    if ($validator->passes()) {
    Permission::create([
        'name' => $request->name,
    ]);
        return redirect()->route('permissions.index')->with('success', 'Privilège créé avec succès.');

}
    else {
            return  redirect()->route('permission.get')->withInput()->withErrors($validator);
}


}


public function edit($id)
{
    $permission = Permission::findOrFail($id); // Récupérer le rôle par son ID
    return view('permission.permission-edit', compact('permission')); // Passer $role et $permissions à la vue


}

public function update(Request $request, $id)
{
    $permission = Permission::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|unique:permissions,name,' . $permission->id . '|min:3',
    ]);

    if ($validator->passes()) {
        $permission->update([
            'name' => $request->name,
        ]);
        return redirect()->route('permissions.index')->with('success', 'Privilège mis à jour avec succès.');
    } else {
        return redirect()->route('permissions.edit', ['id' => $id])->withInput()->withErrors($validator);
    }

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
