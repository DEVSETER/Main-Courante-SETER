<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;



class LocationController extends Controller implements HasMiddleware
{
   public static function middleware(): array
{

  return [
    new Middleware('permission:Consulter liste lieux', only: ['index']),
    new Middleware('permission:Modifier lieu', only: ['edit']),
    new Middleware('permission:Créer lieu', only: ['create']),
    new Middleware('permission:Supprimer lieu', only: ['destroy']),
];
}
    public function index()
    {
        $locations = Location::all();
        return view('location.index', compact('locations'));
    }
    public function create()
    {
        // Logique pour afficher le formulaire de création d'une location
        return view('location.location-create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',

        ]);

        // Enregistrement de la location dans la base de données
        Location::create($validated);

        return redirect()->route('locations.index')->with('success', 'lieu créée avec succès.');
    }
    public function edit($id)
    {
        // Logique pour afficher le formulaire d'édition d'une location
        // Récupération de la location par son ID
        $location = Location::findOrFail($id);
        return view('location.location-edit', compact('location'));
    }
    public function update(Request $request, $id)
    {
        // Logique pour mettre à jour une location existante
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',

        ]);

        // Mise à jour de la location dans la base de données
        $location = Location::findOrFail($id);
        $location->update($validated);

        return redirect()->route('locations.index')->with('success', 'Lieu mise à jour avec succès.');
    }
    public function destroy($id)
    {
        // Logique pour supprimer une location
        $location = Location::findOrFail($id);
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Location supprimée avec succès.');
    }
    }



