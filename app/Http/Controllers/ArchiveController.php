<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Action;
use App\Models\Entite;
use App\Models\Impact;
use App\Models\Location;
use App\Models\Evenement;
use App\Models\Action_user;
use App\Models\Commentaire;
use App\Models\Liste_diffusion;
use App\Models\Nature_evenement;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ActionNotificationToUser;
use App\Mail\ActionNotificationGeneric;
use Illuminate\Http\Request;

class ArchiveController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Consulter liste archive', only: ['index']),
            new Middleware('permission:Modifier archive', only: ['edit', 'update']),
        ];
    }


public function index()
{
    $user = Auth::user();
    $entiteCode = $user->entite->code ?? null;

    // Événements et colonnes par entité
    $evenementsSRCOF = Evenement::whereHas('entite', function($query) {
    $query->where('code', 'SR COF');
        })
        ->where('statut', 'archivé')
        ->with(['location', 'nature_evenement', 'impact', 'actions', 'commentaires', 'entite'])
        ->get();
    $colonnesSRCOF = [
        'date_evenement',
        'nature_evenement',
        'location',
        'description',
        'consequence_sur_pdt',
        'redacteur',
        'commentaire'
    ];

    $evenementsCIV = Evenement::whereHas('entite', function($query) {
        $query->where('code', 'CIV');
    })
    ->where('statut', 'archivé')
    ->with(['location', 'nature_evenement', 'impact', 'actions', 'commentaires', 'entite'])
    ->get();
    $colonnesCIV = [
        'date_evenement',
        'nature_evenement',
        'location',
        'description',
        'consequence_sur_pdt',
        'redacteur',
        'commentaire',
        'confidentialite',
        'post'
    ];

    $evenementsHOTLINE = Evenement::whereHas('entite', function($query) {
        $query->where('code', 'HC');
        })
        ->where('statut', 'archivé')
    ->with(['location', 'nature_evenement', 'impact', 'actions', 'commentaires', 'entite'])
    ->get();
    $colonnesHOTLINE = [
        'date_evenement',
        'nature_evenement',
        'description',
        'consequence_sur_pdt',
        'redacteur',
        'commentaire',
        'redacteur',
        'post'
    ];

    $evenementsCM = Evenement::whereHas('entite', function($query) {
    $query->where('code', 'CM');
           })
    ->where('statut', 'archivé')
    ->with(['location', 'nature_evenement', 'impact', 'actions', 'commentaires', 'entite'])
    ->get();
    $colonnesCM = [
        'date_evenement',
        'nature_evenement',
        'description',
        'redacteur',
        'commentaire',
    ];

    $evenementsPTP = Evenement::whereHas('entite', callback: function($query) {
        $query->where('code', 'PTP');
        })
    ->where('statut', 'archivé')
    ->with(['location', 'nature_evenement', 'impact', 'actions', 'commentaires', 'entite'])
    ->get();
    $colonnesPTP = [
        'date_evenement',
        'nature_evenement',
        'location',
        'description',
        'consequence_sur_pdt',
        'redacteur'
    ];

    $nature_evenements = Nature_evenement::all();
    $locations = Location::all();
    $impacts = Impact::all();
    $entites = Entite::all();
    $listesDiffusion = Liste_diffusion::with('users')->get();
    $actions = Action::all();

$evenements = Evenement::with(['location', 'nature_evenement', 'impact', 'actions','actions.auteur','commentaires','commentaires.auteur', 'entite'])->get();

    $defaultTab = 'SRCOF';
    if ($entiteCode === 'CIV') {
        $defaultTab = 'CIV';
    } elseif ($entiteCode === 'HC') {
        $defaultTab = 'HOTLINE';
    } elseif ($entiteCode === 'CM') {
        $defaultTab = 'CM';
    } elseif ($entiteCode === 'PTP') {
        $defaultTab = 'PTP';
    }

    return view('evenement.archive', compact(
        'evenementsSRCOF', 'colonnesSRCOF',
        'evenementsCIV', 'colonnesCIV',
        'evenementsHOTLINE', 'colonnesHOTLINE',
        'evenementsCM', 'colonnesCM',
        'evenementsPTP', 'colonnesPTP',
        'nature_evenements', 'locations', 'impacts', 'entites', 'evenements', 'user', 'entiteCode', 'defaultTab','listesDiffusion'
    ));
}
public function unarchive(Evenement $evenement)
{
    try {
        // Vérifier que l'événement est bien archivé
        if ($evenement->statut !== 'archive') {
            return response()->json([
                'success' => false,
                'message' => 'Cet événement n\'est pas archivé'
            ], 400);
        }

        // Mettre à jour le statut
        $evenement->update([
            'statut' => 'en_cours',
            'updated_at' => now()
        ]);



        return response()->json([
            'success' => true,
            'message' => 'Événement désarchivé avec succès',

        ]);

    } catch (\Exception $e) {
        // \Log::error('Erreur lors du désarchivage de l\'événement ' . $evenement->id . ': ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du désarchivage : ' . $e->getMessage()
        ], 500);
    }

}
public function destroy($id)
{
    try {
        // Logique pour supprimer un événement
        $evenement = Evenement::findOrFail($id);

        // Sauvegarder les infos avant suppression pour le log
        $evenementInfo = [
            'id' => $evenement->id,
            'nature' => $evenement->nature_evenement->libelle ?? 'Non définie',
            'date' => $evenement->date_evenement
        ];

        $evenement->delete();

        Log::info('Événement supprimé avec succès', $evenementInfo);

        // Vérifier si la requête attend une réponse JSON
        if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Événement supprimé avec succès.'
            ]);
        }

        return redirect()->route('evenements.index')->with('success', 'Événement supprimé avec succès.');

    } catch (\Exception $e) {
        Log::error('Erreur lors de la suppression de l\'événement ' . $id . ': ' . $e->getMessage());

        // Réponse JSON en cas d'erreur
        if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ], 500);
        }

        return redirect()->route('evenements.index')->with('error', 'Erreur lors de la suppression de l\'événement.');
    }
}
}
