<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CommentaireController extends Controller implements HasMiddleware
{
    public static function middleware(): array
{
        return [
            new Middleware('permission:Modifier commentaire', only: ['edit']),
            new Middleware('permission:Supprimer commentaire', only: ['destroy']),
        ];
    }


public function update(Request $request, Commentaire $commentaire)
    {
        try {
            $validated = $request->validate([
                'text' => 'required|string|max:2000'
            ]);

            // Vérifier que l'utilisateur peut modifier ce commentaire
            // if ($commentaire->redacteur != Auth::id()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Vous ne pouvez modifier que vos propres commentaires'
            //     ], 403);
            // }

            $commentaire->update([
                'text' => $validated['text'],
                'date' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Commentaire modifié avec succès',
                'commentaire' => $commentaire->load('auteur')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification: ' . $e->getMessage()
            ], 500);
        }
    }

public function destroy(Commentaire $commentaire)
    {
        try {
            // Vérifier que l'utilisateur peut supprimer ce commentaire
        //   if ($commentaire->redacteur !== Auth::user()->nom) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Vous ne pouvez supprimer que vos propres commentaires'
        //         ], 403);
        //     }

            $commentaire->delete();

            return response()->json([
                'success' => true,
                'message' => 'Commentaire supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
}
