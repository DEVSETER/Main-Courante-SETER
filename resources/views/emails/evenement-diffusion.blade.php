<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diffusion Événement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #67152e, #8b1538);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin: 5px;
        }
        .badge-urgent { background: #f8d7da; color: #721c24; }
        .badge-normal { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            border-left: 4px solid #67152e;
            background: #f8f9fa;
        }
        .section h3 {
            margin-top: 0;
            color: #67152e;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }
        .info-item {
            background: white;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }
        .info-label {
            font-weight: bold;
            color: #67152e;
            font-size: 12px;
            text-transform: uppercase;
        }
        .info-value {
            margin-top: 5px;
            font-size: 14px;
        }
        .action-item, .comment-item {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
        }
        .action-header, .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }
        .footer {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .message-personnalise {
            background: #e7f3ff;
            border: 1px solid #b3d7ff;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- En-tête -->
        <div class="header">
            <h1>📋 Diffusion d'Événement</h1>
            <p>Événement #{{ $evenement->id }} - {{ isset($evenement->nature_evenement) ? $evenement->nature_evenement->libelle : 'Non défini' }}</p>
        </div>

        <!-- Message personnalisé -->
        @if($messagePersonnalise)
        <div class="message-personnalise">
            <h4>💬 Message de {{ isset($expediteur->prenom) ? $expediteur->prenom : '' }} {{ isset($expediteur->nom) ? $expediteur->nom : '' }} :</h4>
            <p>{{ $messagePersonnalise }}</p>
        </div>
        @endif

        <!-- Informations principales -->
        <div class="section">
            <h3>📋 Détails de l'événement</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">📅 Date et heure</div>
                    <div class="info-value">
                        {{ $evenement->date_evenement ? \Carbon\Carbon::parse($evenement->date_evenement)->format('d/m/Y à H:i') : 'Non définie' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">📍 Localisation</div>
                    <div class="info-value">{{ isset($evenement->location->libelle) ? $evenement->location->libelle : 'Non définie' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">⚡ Statut</div>
                    <div class="info-value">
                        @php
                            $statutLabel = match($evenement->statut) {
                                'en_cours' => 'En cours',
                                'cloture' => 'Terminé',
                                'archive' => 'Archivé',
                                default => $evenement->statut
                            };
                            $badgeClass = match($evenement->statut) {
                                'en_cours' => 'badge-urgent',
                                'cloture' => 'badge-info',
                                'archive' => 'badge-normal',
                                default => 'badge-normal'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $statutLabel }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">👤 Rédacteur</div>
                    <div class="info-value">{{ $evenement->redacteur ?: 'Non défini' }}</div>
                </div>

                @if($evenement->consequence_sur_pdt !== null)
                <div class="info-item">
                    <div class="info-label">⚠️ Conséquence PDT</div>
                    <div class="info-value">
                        <span class="badge {{ $evenement->consequence_sur_pdt ? 'badge-urgent' : 'badge-info' }}">
                            {{ $evenement->consequence_sur_pdt ? 'OUI' : 'NON' }}
                        </span>
                    </div>
                </div>
                @endif

                @if($evenement->impact)
                <div class="info-item">
                    <div class="info-label">💥 Impact</div>
                    <div class="info-value">{{ $evenement->impact->libelle }}</div>
                </div>
                @endif

                <div class="info-item">
                    <div class="info-label">🏢 Entité</div>
                    <div class="info-value">{{ isset($evenement->entite->nom) ? $evenement->entite->nom : (isset($evenement->entite->code) ? $evenement->entite->code : 'Non définie') }}</div>
                </div>
            </div>
        </div>

        <!-- Description -->
        @if($evenement->description)
        <div class="section">
            <h3>📝 Description</h3>
            <p>{{ $evenement->description }}</p>
        </div>
        @endif

        <!-- Actions -->
        @if($inclureActions && $evenement->actions && $evenement->actions->count() > 0)
        <div class="section">
            <h3>🎯 Actions ({{ $evenement->actions->count() }})</h3>
            @foreach($evenement->actions->take(10) as $action)
            <div class="action-item">
                <div class="action-header">
                    <span class="badge badge-info">
                        @switch($action->type)
                            @case('demande_validation') 🔍 Demande de validation @break
                            @case('aviser') ⚠️ Avis @break
                            @case('informer') 📢 Information @break
                            @case('diffusion') 📤 Diffusion @break
                            @default 💬 {{ ucfirst($action->type) }}
                        @endswitch
                    </span>
                    <small>{{ $action->created_at ? $action->created_at->format('d/m/Y H:i') : 'Date inconnue' }}</small>
                </div>
                <p>{{ $action->commentaire }}</p>
                @if($action->message)
                    <p><em>Message : {{ $action->message }}</em></p>
                @endif
                @if($action->auteur)
                    <small><strong>Par :</strong> {{ $action->auteur->prenom }} {{ $action->auteur->nom }}</small>
                @endif
            </div>
            @endforeach

            @if($evenement->actions->count() > 10)
            <p><em>... et {{ $evenement->actions->count() - 10 }} autre(s) action(s)</em></p>
            @endif
        </div>
        @endif

        <!-- Commentaires -->
        @if($inclureCommentaires && $evenement->commentaires && $evenement->commentaires->count() > 0)
        <div class="section">
            <h3>💬 Commentaires ({{ $evenement->commentaires->count() }})</h3>
            @foreach($evenement->commentaires->take(5) as $commentaire)
            <div class="comment-item">
                <div class="comment-header">
                    <span class="badge badge-normal">💬 Commentaire</span>
                    <small>{{ $commentaire->created_at ? $commentaire->created_at->format('d/m/Y H:i') : 'Date inconnue' }}</small>
                </div>
                <p>{{ $commentaire->text ?: ($commentaire->commentaire ?: 'Commentaire vide') }}</p>
                @if($commentaire->auteur)
                    <small><strong>Par :</strong> {{ $commentaire->auteur->prenom }} {{ $commentaire->auteur->nom }}</small>
                @endif
            </div>
            @endforeach

            @if($evenement->commentaires->count() > 5)
            <p><em>... et {{ $evenement->commentaires->count() - 5 }} autre(s) commentaire(s)</em></p>
            @endif
        </div>
        @endif
            <p>
                <a href="{{ $eventUrl }}" style="color: #007bff; font-weight: bold;">
                    👉 Voir l'événement dans la main courante
                </a>
            </p>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Main Courante Électronique</strong></p>
            <p>Diffusé le {{ now()->format('d/m/Y à H:i') }} par {{ isset($expediteur->prenom) ? $expediteur->prenom : '' }} {{ isset($expediteur->nom) ? $expediteur->nom : '' }}</p>
            <p><em>Cet email est généré automatiquement, merci de ne pas y répondre.</em></p>
        </div>
    </div>
</body>
</html>
