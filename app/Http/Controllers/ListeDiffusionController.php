<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Liste_diffusion;

use Illuminate\Http\Request;

class ListeDiffusionController extends Controller
{
     public function index()
    {
        $listes = Liste_diffusion::with('users')->get();
        // dd('ok');
        return view('listeDiffusion.index', compact('listes'));
    }

    public function create()
    {
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

        if ($request->has('users')) {
            $liste->users()->sync($request->users);
        }

        return redirect()->route('liste_diffusions.index')->with('success', 'Liste de diffusion créée avec succès.');
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

    public function destroy($id)
    {
        $liste = Liste_diffusion::findOrFail($id);
        $liste->delete();

        return redirect()->route('liste_diffusions.index')->with('success', 'Liste de diffusion supprimée avec succès.');
    }
}
