<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ActionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Modifier action', only: ['edit']),
            new Middleware('permission:Supprimer action', only: ['destroy']),
        ];
    }

// Dans ActionController.php
public function update(Request $request, Action $action)
{
    $validated = $request->validate([
        'type' => 'required|string|in:texte_libre,demande_validation,aviser,informer',
        'commentaire' => 'required|string|max:1000',
        'message' => 'nullable|string|max:1000'
    ]);

    $action->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Action modifiée avec succès',
        'action' => $action->load('auteur')
    ]);
}

public function destroy(Action $action)
{
    $action->delete();

    return response()->json([
        'success' => true,
        'message' => 'Action supprimée avec succès'
    ]);
}
}
