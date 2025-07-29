<?php

namespace App\Http\Controllers;

use App\Models\Nature_evenement;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
class NatureEvenementController extends Controller implements HasMiddleware
{
   public static function middleware(): array
{

  return [
    new Middleware('permission:Consulter liste nature événements', only: ['index']),
    new Middleware('permission:Modifier nature événements', only: ['edit']),
    new Middleware('permission:Créer nature événements', only: ['create']),
    new Middleware('permission:Supprimer nature événements', only: ['destroy']),
];
}
public function index()
{
    // dd('ok');
    $natures = Nature_evenement::all();
    return view('natureEvenement.index', compact('natures'));

}
public function create()
{
    return view('natureEvenement.natureEvenement-create');



}
public function store(Request $request)
{
    $request->validate([
        'libelle' => 'required|string|max:255',

    ]);

    Nature_evenement::create([
        'libelle' => $request->libelle,

    ]);

    return redirect()->route('nature_evenements.index')->with('success', 'Nature d\'événement créée avec succès.');
}
public function edit($id)
{
    $nature = Nature_evenement::findOrFail($id);
    return view('natureEvenement.natureEvenement-edit', compact('nature'));
}
public function update(Request $request, $id)
{

    $request->validate([
        'libelle' => 'required|string|max:255',
    ]);

    $nature = Nature_evenement::findOrFail($id);
    $nature->update([
        'libelle' => $request->libelle,
    ]);

    return redirect()->route('nature_evenements.index')->with('success', 'Nature d\'événement mise à jour avec succès.');
}
public function destroy($id)
{
    $nature = Nature_evenement::findOrFail($id);
    $nature->delete();

    return redirect()->route('nature_evenements.index')->with('success', 'Nature d\'événement supprimée avec succès.');
}
}






