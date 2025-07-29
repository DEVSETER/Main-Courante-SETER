<?php

namespace App\Exports;

use App\Models\Evenement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class RapportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
        protected $evenements;
    public function __construct(Collection $evenements)
    {
        $this->evenements = $evenements;
    }

    public function collection()
    {
         return $this->evenements;
    }
     public function headings(): array
    {
        return [
            'id',
            'Date et heure',
            'Nature de l\'événement',
            'Localisation',
            'Description',
            'Conséquence sur le PDT',
            'Rédacteur',
            'Statut',
            'Date clôture',
            'Confidentialité',
            'Impact',
            'Heure appel intervenant',
            'Heure arrivée intervenant',
            'Commentaire',
            'Action',
            'POST',

        ];
    }
    public function map($evt): array
    {
          return [
        $evt->id ?? '',
        $evt->date_evenement ?? '',
        $evt->nature_evenement?->libelle ?? '',
        $evt->location?->libelle ?? '',
        $evt->description ?? '',
        ($evt->consequence_sur_pdt == 1) ? 'OUI' : (($evt->consequence_sur_pdt === 0) ? 'NON' : ''),
        $evt->redacteur ?? '',
        $evt->statut ?? '',
        $evt->date_cloture ? (
            $evt->date_cloture instanceof \DateTimeInterface
                ? $evt->date_cloture->format('Y-m-d H:i:s')
                : $evt->date_cloture
        ) : '',
        $evt->confidentialite == 1 ? 'Confidentiel' : 'Non confidentiel',
        $evt->impacts && method_exists($evt->impacts, 'pluck') ? $evt->impacts->pluck('libelle')->implode(', ') : '',
        $evt->heure_appel_intervenant ? (
            $evt->heure_appel_intervenant instanceof \DateTimeInterface
                ? $evt->heure_appel_intervenant->format('Y-m-d H:i:s')
                : $evt->heure_appel_intervenant
        ) : '',
        $evt->heure_arrive_intervenant ? (
            $evt->heure_arrive_intervenant instanceof \DateTimeInterface
                ? $evt->heure_arrive_intervenant->format('Y-m-d H:i:s')
                : $evt->heure_arrive_intervenant
        ) : '',
        // Commentaires
        $evt->commentaires && method_exists($evt->commentaires, 'pluck')
            ? $evt->commentaires->pluck('libelle')->implode(', ')
            : '',
        // Action (exemple : concatène tous les commentaires des actions)
        $evt->actions && method_exists($evt->actions, 'pluck')
            ? $evt->actions->pluck('commentaire')->filter()->implode(' | ')
            : '',
        // POST (PST)
        $evt->entite ?? '',
        // Actions (exemple : concatène tous les types ou libellés d'actions)
        $evt->actions && method_exists($evt->actions, 'pluck')
            ? $evt->actions->pluck('libelle')->filter()->implode(' | ')
            : '',
    ];
    }
        public function styles(Worksheet $sheet)
    {
        // Style pour l'en-tête
        $sheet->getStyle('A1:Q1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '156082'],
            ],
        ]);
        // Largeur automatique pour toutes les colonnes
        foreach (range('A', 'Q') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        return [];
    }
}

   //  evt.id ?? '',
    //             evt.date_evenement
    //                 ? `<span style="white-space:nowrap;">${new Date(evt.date_evenement).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })}</span>`
    //                 : '',
    //             evt.nature_evenement ? evt.nature_evenement.libelle : (evt.nature_evenement_id ?? ''),
    //             evt.location ? evt.location.libelle : (evt.location_id ?? ''),
    //             evt.description ?? '',
    //             (evt.consequence_sur_pdt == 1) ? 'OUI' : (evt.consequence_sur_pdt == 0) ? 'NON' : '',
    //             evt.redacteur ?? '',
    //             evt.statut == 'en_cours'
    //                 ? `<span class="badge badge-outline-success">En cours</span>`
    //                 : evt.statut == 'cloture'
    //                     ? `<span class="badge badge-outline-danger">Clôturé</span>`
    //                     : '',
    //             (evt.location_description ?? ''),
    //             evt.date_cloture ? new Date(evt.date_cloture).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' }) : '',
    //             evt.confidentialite == 1 ? 'Confidentiel' : (evt.confidentialite == 0 ? 'Non confidentiel' : ''),
    //             evt.impacts && evt.impacts.length ? evt.impacts.map(i => i.libelle).join(', ')
    //                 : (evt.impact_id ?? ''),
    //             evt.heure_appel_intervenant ? new Date(evt.heure_appel_intervenant).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' }) : '',
    //             evt.heure_arrive_intervenant ? new Date(evt.heure_arrive_intervenant).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' }) : '',
    //             evt.commentaires && evt.commentaires.length
    //                 ? evt.commentaires.map(c => c.text).join('<br>') : '',
    //             `<div style="display:flex;flex-wrap:wrap;gap:4px;">
    //                 ${uniqueActions && uniqueActions.length
    //                     ? uniqueActions.filter(a => a.commentaire && a.commentaire.trim() !== '')
    //                         .map(a => `<span class="badge ${typeColors[a.type] || 'badge-outline-secondary'}">${a.commentaire}</span>`)
    //                         .join('') : ''}
    //             </div>`,
    //             evt.entite && evt.entite.nom ? evt.entite.nom : '',
