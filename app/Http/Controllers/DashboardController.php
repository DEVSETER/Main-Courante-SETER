<?php
// filepath: c:\Projet\MainCourante\MainCourante\MainCourante\app\Http\Controllers\DashboardController.php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\NatureEvenement;
use App\Models\Entite;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Début et fin du mois en cours
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // ✅ Statistiques du mois en cours par entité
        $countSRCOF = Evenement::whereHas('entite', function($query) {
                $query->where('code', 'SR COF');
            })
            ->whereBetween('date_evenement', [$startOfMonth, $endOfMonth])
            ->count();

        $countCIV = Evenement::whereHas('entite', function($query) {
                $query->where('code', 'CIV');
            })
            ->whereBetween('date_evenement', [$startOfMonth, $endOfMonth])
            ->count();

        $countCM = Evenement::whereHas('entite', function($query) {
                $query->where('code', 'CM');
            })
            ->whereBetween('date_evenement', [$startOfMonth, $endOfMonth])
            ->count();

        $countHotline = Evenement::whereHas('entite', function($query) {
                $query->where('code', 'HC');
            })
            ->whereBetween('date_evenement', [$startOfMonth, $endOfMonth])
            ->count();

        $countPTP = Evenement::whereHas('entite', function($query) {
                $query->where('code', 'PTP');
            })
            ->whereBetween('date_evenement', [$startOfMonth, $endOfMonth])
            ->count();

        // ✅ Total des événements du mois
        $evenementsCount = Evenement::whereBetween('date_evenement', [$startOfMonth, $endOfMonth])->count();

        // ✅ Données pour les graphiques par entité et nature
        $eventsByEntityAndNature = DB::table('evenements')
            ->join('entites', 'evenements.entite_id', '=', 'entites.id')
            ->join('nature_evenements', 'evenements.nature_evenement_id', '=', 'nature_evenements.id')
            ->select(
                'entites.nom as entite_nom',
                'entites.code as entite_code',
                'nature_evenements.libelle as nature_libelle',
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('evenements.date_evenement', [$startOfMonth, $endOfMonth])
            ->groupBy('entites.id', 'entites.nom', 'entites.code', 'nature_evenements.id', 'nature_evenements.libelle')
            ->orderBy('entites.code')
            ->orderBy('nature_evenements.libelle')
            ->get();

        // ✅ Données pour le graphique chronologique (derniers 12 mois)
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i)->endOfMonth();

            $monthlyData[] = [
                'month' => $monthStart->format('M Y'),
                'month_short' => $monthStart->format('M'),
                'count' => Evenement::whereBetween('date_evenement', [$monthStart, $monthEnd])->count()
            ];
        }

        // ✅ Statistiques par statut (du mois)
        $eventsByStatus = Evenement::select('statut', DB::raw('COUNT(*) as count'))
            ->whereBetween('date_evenement', [$startOfMonth, $endOfMonth])
            ->groupBy('statut')
            ->get()
            ->mapWithKeys(function ($item) {
                $statusLabels = [
                    'en_cours' => 'En cours',
                    'cloture' => 'Terminé',
                    'archive' => 'Archivé'
                ];
                return [$statusLabels[$item->statut] ?? $item->statut => $item->count];
            });

        return view('index', compact(
            'countSRCOF',
            'countCIV',
            'countCM',
            'countHotline',
            'countPTP',
            'evenementsCount',
            'eventsByEntityAndNature',
            'monthlyData',
            'eventsByStatus'
        ));
    }
}
