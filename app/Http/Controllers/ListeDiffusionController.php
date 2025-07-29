<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Liste_diffusion;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ListeDiffusionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
{

  return [
    new Middleware('permission:Consulter liste diffusion', only: ['index']),
    new Middleware('permission:Modifier liste diffusion', only: ['edit']),
    new Middleware('permission:Créer liste diffusion', only: ['create']),
    new Middleware('permission:Supprimer liste diffusion', only: ['destroy']),
];
}
     public function index()
    {

        $listes = Liste_diffusion::with('users')->get();
        // dd('ok');
        return view('listeDiffusion.index', compact('listes'));
    }

    public function create()

    {

    // $permissions = Permission::all()->groupBy('type');
    // return view('role.role-create', compact('permissions'));

        $users = User::all();
        return view('listeDiffusion.listeDiffusion-create', compact('users'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nom' => 'required|string|max:255',
            'users' => 'nullable|array',
        ]);

        $liste = Liste_diffusion::create(['nom' => $request->nom]);
        $liste = $liste->fresh(); // ou ListeDiffusion::find($liste->id)

        if ($request->has('users')) {
            $liste->users()->sync($request->users);
        }

        return redirect()->route('liste_diffusions.index')->with('success', 'Liste de diffusion créée avec succès.');
    }

    // public function edit($id)
    // {
    //     $liste = Liste_diffusion::with('users')->findOrFail($id);
    //     $users = User::all();
    //     return view('listeDiffusion.listeDiffusion-edit', compact('liste', 'users'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'nom' => 'required|string|max:255',
    //         'users' => 'nullable|array',
    //     ]);

    //     $liste = Liste_diffusion::findOrFail($id);
    //     $liste->update(['nom' => $request->nom]);

    //     if ($request->has('users')) {
    //         $liste->users()->sync($request->users);
    //     }

    //     return redirect()->route('liste_diffusions.index')->with('success', 'Liste de diffusion mise à jour avec succès.');
    // }

    public function destroy($id)
    {
        $liste = Liste_diffusion::findOrFail($id);
        $liste->delete();

        return redirect()->route('liste_diffusions.index')->with('success', 'Liste de diffusion supprimée avec succès.');
    }

public function edit($id)
{
    $liste = Liste_diffusion::with('users')->findOrFail($id);
    $users = User::all();
    return view('listeDiffusion.listeDiffusion-edit', compact('liste', 'users'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nom' => 'required|string|max:255',
        'users' => 'nullable|array',
    ]);

    $liste = Liste_diffusion::findOrFail($id);
    $liste->update(['nom' => $request->nom]);

    if ($request->has('users')) {
        $liste->users()->sync($request->users);
    }

    return redirect()->route('liste_diffusions.index')->with('success', 'Liste de diffusion mise à jour avec succès.');
}
}
