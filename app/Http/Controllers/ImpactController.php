<?php

namespace App\Http\Controllers;

use App\Models\Impact;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ImpactController extends Controller implements HasMiddleware
{

      public static function middleware(): array
    {
        return [
            new Middleware('permission:Consulter liste impacts', only: ['index']),
            new Middleware('permission:Créer impact', only: ['create', 'store']),
            new Middleware('permission:Modifier impact', only: ['edit', 'update']),
            new Middleware('permission:Supprimer impact', only: ['destroy']),
        ];
    }
    public function index()
    {
        $impacts = Impact::all(); // Logique pour récupérer les impacts depuis la base de données
        return view('impact.index', compact('impacts'));
    }

    public function create()
    {

        return view('impact.impact-create');
    }

    public function store(Request $request)
    {

   $validated = $request->validate([
            'libelle' => 'required|string|max:255',

        ]);
        $impact = Impact::create($validated);
        return redirect()->route('impacts.index')->with('success', 'Impact créé avec succès.');
    }

    public function edit($id)
    {
       $impact = Impact::findOrFail($id); // Récupération de l'impact par son ID
        if (!$impact) {
            return redirect()->route('impacts.index')->with('error', 'Impact non trouvé.');
        }

        return view('impact.impact-edit', compact('impact'));
    }

    public function update(Request $request, $id)
    {
 $validated = $request->validate([
            'libelle' => 'required|string|max:255',

        ]);
        $impact = Impact::findOrFail($id);
        $impact->update($validated);
        return redirect()->route('impacts.index')->with('success', 'Impact mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $impact = Impact::findOrFail($id);
        if (!$impact) {
            return redirect()->route('impacts.index')->with('error', 'Impact non trouvé.');
        }
        $impact->delete();
        return redirect()->route('impacts.index')->with('success', 'Impact supprimé avec succès.');
    }
    public function show($id)
    {
        // Logique pour afficher les détails d'un impact
        return view('impact.show', compact('id'));
    }
    public function search(Request $request)
    {
        // Logique pour rechercher des impacts
        $query = $request->input('query');
        // Effectuer la recherche dans la base de données
        return view('impact.search', compact('query'));
    }
}
