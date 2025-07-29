{{-- filepath: resources/views/rapports/export.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des événements</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        th, td { border: 1px solid #888; padding: 2px 3px; word-break: break-word; }
        th { background: #67152e; color: #fff; font-size: 7px; }
        h2 { color: #67152e; }
        /* Largeur fixe pour chaque colonne (adapte si besoin) */
        th, td { max-width: 80px; font-size: 5px; }
    </style>
</head>
<body>
    <h2>Rapport des événements ({{ ucfirst($type) }})</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Nature</th>
                <th>Lieu</th>
                <th>Description</th>
                <th>PDT</th>
                <th>Rédacteur</th>
                <th>Statut</th>
                <th>Clôture</th>
                <th>Conf.</th>
                <th>Impact</th>
                <th>Appel</th>
                <th>Arrivée</th>
                <th>Commentaires</th>
                <th>Actions</th>
                <th>POST</th>
            </tr>
        </thead>
        <tbody>
            @foreach($evenements as $evt)
                <tr>
                    <td>{{ $evt->id }}</td>
                    <td>{{ $evt->date_evenement }}</td>
                    <td>{{ $evt->nature_evenement->libelle ?: '' }}</td>
                    <td>{{ $evt->location->libelle ?: '' }}</td>
                    <td>{{ $evt->description }}</td>
                    <td>
                        @if($evt->consequence_sur_pdt === 1) OUI
                        @elseif($evt->consequence_sur_pdt === 0) NON
                        @endif
                    </td>
                    <td>{{ $evt->redacteur }}</td>
                    <td>{{ $evt->statut }}</td>
                    <td>{{ $evt->date_cloture }}</td>
                    <td>{{ $evt->confidentialite == 1 ? 'Conf.' : 'Non conf.' }}</td>
                    <td>
                        @if($evt->impacts && method_exists($evt->impacts, 'pluck'))
                            {{ $evt->impacts->pluck('libelle')->implode(', ') }}
                        @endif
                    </td>
                    <td>{{ $evt->heure_appel_intervenant }}</td>
                    <td>{{ $evt->heure_arrive_intervenant }}</td>
                    <td>
                        @if($evt->commentaires && method_exists($evt->commentaires, 'pluck'))
                            {{ $evt->commentaires->pluck('libelle')->implode(', ') }}
                        @endif
                    </td>
                    <td>
                        @if($evt->actions && method_exists($evt->actions, 'pluck'))
                            {{ $evt->actions->pluck('libelle')->implode(' | ') }}
                        @endif
                    </td>
                    <td>{{ $evt->entite ?: '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
