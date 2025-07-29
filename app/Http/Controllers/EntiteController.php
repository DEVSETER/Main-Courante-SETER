<?php

namespace App\Http\Controllers;

use App\Models\Entite;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;


class EntiteController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:Consulter liste entités', only: ['index']),
            new Middleware('permission:Créer entité', only: ['create', 'store']),
            new Middleware('permission:Modifier entité', only: ['edit', 'update']),
            new Middleware('permission:Supprimer entité', only: ['destroy']),
            new Middleware('permission:Afficher détails entité', only: ['show']),
            new Middleware('permission:Rechercher entités', only: ['search']),
        ];
    }

public function index()
{

      $entites = Entite::all(); // Récupérer toutes les entites
    return view('entite.index', compact('entites')); // Passer les entites à la vue

}

public function create()
{
    return view('entite.entite-create');
}

//Enregistrement de privilege
//Creation de privilege
  public function store(Request $request)
    {

$validator =Validator::make($request->all(), [
    'nom' => 'required|string|unique:entites,nom|min:1',
]);
    if ($validator->passes()) {
    entite::create([
        'nom' => $request->nom,
        'code' => $request->code,
    ]);
        return redirect()->route('entites.index')->with('success', 'Privilège créé avec succès.');

}
    else {
            return  redirect()->route('entite.get')->withInput()->withErrors($validator);
}


}

public function json()
{
    return response()->json(
        Evenement::with(['nature_evenement', 'location', 'impacts', 'entite', 'actions', 'commentaires'])->get()
    );
}

public function edit($id)
{
    $entite = entite::findOrFail($id); // Récupérer le rôle par son ID
    return view('entite.entite-edit', compact('entite')); // Passer $role et $entites à la vue


}

// public function update(Request $request, $id)
// {
//     $entite = entite::findOrFail($id);

//     $validator = Validator::make($request->all(), [
//         'nom' => 'required|string|unique:entites,nom,' . $entite->id . '|min:3',
//         'code' => 'required|string|unique:entites,code,' . $entite->id . '|min:3',
//     ]);

//     if ($validator->passes()) {
//         $entite->update([
//             'nom' => $request->nom,
//             'code' => $request->code,
//         ]);
//         return redirect()->route('entites.index')->with('success', 'Privilège mis à jour avec succès.');
//     } else {
//         return redirect()->route('entites.edit', ['id' => $id])->withInput()->withErrors($validator);
//     }

// }

public function update(Request $request, $id)
{
    // Récupérer l'entité ou renvoyer une erreur 404 si elle n'existe pas
    $entite = Entite::findOrFail($id);

    $request->validate([
        'nom' => 'required|string|min:1|unique:entites,nom,' . $entite->id,
        'code' => 'required|string|min:1|unique:entites,code,' . $entite->id,
    ]);

    $entite->update([
        'nom' => $request->nom,
        'code' => $request->code,
    ]);

    return redirect()->route('entites.index')->with('success', 'Entité mis à jour avec succès.');
}
public function destroy(Request $request)
{
   $id=$request->id;
    $entite = entite::findOrFail($id);
    if($entite== null){
        return redirect()->route('entites.index')->with('error', 'Privilège introuvable.');
    }
    $entite->delete();

    return redirect()->route('entites.index')->with('success', 'Privilège supprimé avec succès.');
}
}


