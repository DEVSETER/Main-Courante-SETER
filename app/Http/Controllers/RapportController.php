<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EvenementsExport;
use App\Models\Evenement;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use App\Exports\RapportExport;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RapportController extends Controller implements HasMiddleware
{

   public static function middleware(): array
    {
        return [
            new Middleware('permission:Consulter rapports', only: ['index']),


        ];
    }
public function index(){

          // Rapports du jour, semaine, mois en cours (déjà existant)
    $evenements = Evenement::all();
    $daily = $this->getEvenements('daily');
    $weekly = $this->getEvenements('weekly');
    $monthly = $this->getEvenements('monthly');

    // Générer les 7 derniers jours
    $jours = [];
    for ($i = 0; $i < 7; $i++) {
        $jours[] = Carbon::today()->subDays($i)->format('Y-m-d');
    }

    // Générer les 6 derniers mois
    $mois = [];
    for ($i = 0; $i < 6; $i++) {
        $mois[] = Carbon::now()->subMonths($i)->format('Y-m');
    }

    return view('rapports.index', compact('daily', 'weekly', 'monthly', 'evenements', 'jours', 'mois'));
}
public function exportExcel(){
    return Excel::download(new \App\Exports\EvenementsExport, 'rapport_evenements.xlsx');
}

//     public function export(Request $request)
// {
//     $query = Evenement::query();

//     if ($request->filled('date_debut')) {
//         $query->whereDate('date_evenement', '>=', $request->date_debut);
//     }

//     if ($request->filled('date_fin')) {
//         $query->whereDate('date_evenement', '<=', $request->date_fin);
//     }

//     // Ajoute d'autres filtres ici

//     $evenements = $query->get();

//     return Excel::download(new EvenementsExport($evenements), 'rapport_evenements.xlsx');
// }



public function export($type, $format)
    {
        $evenements = $this->getEvenements($type);
        $typeLabels = [
                'daily' => 'journalier',
                'weekly' => 'hebdomadaire',
                'monthly' => 'mensuel',
            ];
            $typeLabel = $typeLabels[$type] ?? $type;
            $now = Carbon::now()->format('Y-m-d_H-i-s');

        if ($format === 'pdf') {
          $filename = "rapport_{$typeLabel}_{$now}.pdf";
          $pdf = PDF::loadView('rapports.export', compact('evenements', 'type'));
            return $pdf->download($filename);
        }

        if ($format === 'excel') {
            $filename = "rapport_{$typeLabel}_{$now}.xlsx";
        return Excel::download(new RapportExport($evenements), $filename);
        }

        abort(404);
    }

public function exportByDate($date, $format)
{
    $evenements = Evenement::whereDate('created_at', $date)->get();
    $now = Carbon::now()->format('Y-m-d_H-i-s');
    $filename = "rapport_journalier_{$date}_{$now}." . ($format === 'excel' ? 'xlsx' : 'pdf');

    if ($format === 'excel') {
        return Excel::download(new RapportExport($evenements), $filename);
    } else {
        $pdf = PDF::loadView('rapports.export', ['evenements' => $evenements, 'type' => 'journalier']);
        return $pdf->download($filename);
    }
}

public function exportByMonth($month, $format)
{
    $evenements = Evenement::whereYear('created_at', substr($month, 0, 4))
        ->whereMonth('created_at', substr($month, 5, 2))
        ->get();
    $now = Carbon::now()->format('Y-m-d_H-i-s');
    $filename = "rapport_mensuel_{$month}_{$now}." . ($format === 'excel' ? 'xlsx' : 'pdf');

    if ($format === 'excel') {
        return Excel::download(new RapportExport($evenements), $filename);
    } else {
        $pdf = PDF::loadView('rapports.export', ['evenements' => $evenements, 'type' => 'mensuel']);
        return $pdf->download($filename);
    }
}

private function getEvenements($type)
{
    $query = \App\Models\Evenement::query();

        if ($type === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($type === 'weekly') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        } elseif ($type === 'monthly') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }

        return $query->get();
    }
}
