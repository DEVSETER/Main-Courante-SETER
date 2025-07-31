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
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Liste_diffusion;
use App\Mail\EvenementDiffusion;
use App\Models\Nature_evenement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActionNotificationToUser;
use App\Mail\ActionNotificationGeneric;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class EvenementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Consulter liste événements', only: ['index']),
            new Middleware('permission:Créer événement', only: ['create', 'store']),
            new Middleware('permission:Modifier événement', only: ['edit', 'update']),
            new Middleware('permission:Supprimer événement', only: ['destroy']),
            new Middleware('permission:Afficher détails événement', only: ['show']),
            new Middleware('permission:Rechercher événements', only: ['search']),
            new Middleware('permission:Afficher détails événement', only: ['show']),
        ];
    }


public function index()
{
    $user = Auth::user();
    $users = User::with('entite')->get();

    $entiteCode = $user->entite->code ?? null;

    // Événements et colonnes par entité
    $evenementsSRCOF = Evenement::whereHas('entite', function($query) {
    $query->where('code', 'SR COF');
        })
        ->whereIn('statut', ['en_cours', 'cloture']) // Statuts autorisés
        ->where('date_evenement', '>=', now()->subMonth()) // Date des 30 derniers jours
        ->with(['location', 'nature_evenement', 'impact', 'actions', 'commentaires', 'entite'])
        ->get();
    $colonnesSRCOF = [
        'date_evenement',
        'nature_evenement',
        'location',
        'description',
        'consequence_sur_pdt',
        'redacteur',
        'commentaire',
        'visa_encadrant'
    ];

    $evenementsCIV = Evenement::whereHas('entite', function($query) {
        $query->where('code', 'CIV');
    })->whereIn('statut', ['en_cours', 'cloture']) // Statuts autorisés
    ->where('date_evenement', '>=', now()->subMonth()) // Date des 30 derniers jours
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
        })->whereIn('statut', ['en_cours', 'cloture']) // Statuts autorisés
    ->where('date_evenement', '>=', now()->subMonth()) // Date des 30 derniers jours
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
           })->whereIn('statut', ['en_cours', 'cloture']) // Statuts autorisés
    ->where('date_evenement', '>=', now()->subMonth()) // Date des 30 derniers jours
    ->with(['location', 'nature_evenement', 'impact', 'actions', 'commentaires', 'entite'])
    ->get();
    $colonnesCM = [
        'date_evenement',
        'nature_evenement',
        'description',
        'redacteur',
        'commentaires',
        'impact',
        'avis_srcof'
    ];

    $evenementsPTP = Evenement::whereHas('entite', callback: function($query) {
        $query->where('code', 'PTP');
        })->whereIn('statut', ['en_cours', 'cloture']) // Statuts autorisés
        ->where('date_evenement', '>=', now()->subMonth()) // Date des 30 derniers jours
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

    return view('evenement.index', compact(
        'evenementsSRCOF', 'colonnesSRCOF',
        'evenementsCIV', 'colonnesCIV',
        'evenementsHOTLINE', 'colonnesHOTLINE',
        'evenementsCM', 'colonnesCM',
        'evenementsPTP', 'colonnesPTP',
        'nature_evenements', 'locations', 'impacts', 'entites', 'evenements', 'user', 'users', 'entiteCode', 'defaultTab','listesDiffusion'
    ));
}




    public function create()
    {
        // Logique pour afficher le formulaire de création d'un événement
        return view('evenement.evenement-create');
    }


private function getDestinataireNames($destinataires)
{
    $noms = [];

    if (!is_array($destinataires)) {
        return $noms;
    }

    foreach ($destinataires as $destinataireId) {
        if (!is_string($destinataireId)) {
            continue;
        }

        if (strpos($destinataireId, 'liste_') === 0) {
            // C'est une liste de diffusion
            $listeId = str_replace('liste_', '', $destinataireId);
            $liste = Liste_diffusion::find($listeId);
            if ($liste) {
                $noms[] = "📋 {$liste->nom}";
            }
        } else if (strpos($destinataireId, 'user_') === 0) {
            // C'est un utilisateur individuel
            $userId = str_replace('user_', '', $destinataireId);
            $user = User::find($userId);
            if ($user) {
                $noms[] = "👤 {$user->prenom} {$user->nom}";
            }
        }
    }

    return $noms;
}

//  AJOUTER CETTE MÉTHODE pour l'envoi d'emails lors de la création
private function envoyerEmailsAction($evenement, $action, $destinataires, $messagePersonnalise = null)
{
    $emailsAEnvoyer = [];

    // Collecter tous les emails des destinataires
    foreach ($destinataires as $destinataireId) {
        if (strpos($destinataireId, 'liste_') === 0) {
            // Liste de diffusion
            $listeId = str_replace('liste_', '', $destinataireId);
            $liste = Liste_diffusion::with('users')->find($listeId);
            if ($liste && $liste->users) {
                foreach ($liste->users as $user) {
                    if ($user->email && !in_array($user->email, $emailsAEnvoyer)) {
                        $emailsAEnvoyer[] = $user->email;
                    }
                }
            }
        } else if (strpos($destinataireId, 'user_') === 0) {
            // Utilisateur individuel
            $userId = str_replace('user_', '', $destinataireId);
            $user = User::find($userId);
            if ($user && $user->email && !in_array($user->email, $emailsAEnvoyer)) {
                $emailsAEnvoyer[] = $user->email;
            }
        }
    }

    // Envoyer les emails
    if (!empty($emailsAEnvoyer)) {
        foreach ($emailsAEnvoyer as $email) {
            try {
                Mail::to($email)->send(new ActionNotificationGeneric($evenement, $action, $messagePersonnalise));
                Log::info("📧 Email envoyé à {$email} pour l'action {$action->id}");
            } catch (\Exception $e) {
                Log::error("❌ Erreur envoi email à {$email}: " . $e->getMessage());
            }
        }

        // Mettre à jour le statut de l'action
        $action->update(['statut' => 'envoye']);

        Log::info(" " . count($emailsAEnvoyer) . " emails envoyés pour l'action {$action->id}");
    } else {
        Log::warning("⚠️ Aucun email trouvé pour les destinataires de l'action {$action->id}");
    }
}



public function store(Request $request)
{
    try {
        $entiteCode = $request->input('entite') ??
            (Auth::check() && Auth::user()->entite ? Auth::user()->entite->code : null);

        // Validation des champs
        $rules = [
            'nature_evenement_id' => 'nullable|exists:nature_evenements,id',
            'location_id' => 'nullable|integer|exists:locations,id',
            'description' => 'required|string|min:1', //  Obligatoire
            'consequence_sur_pdt' => 'nullable|boolean',
            'redacteur' => 'nullable|string|max:255',
            'statut' => 'required|string|in:en_cours,cloture,archive',
            'date_cloture' => 'nullable|date_format:Y-m-d\TH:i',
            'confidentialite' => 'nullable|boolean',
            'date_evenement' => 'required|date',
            'commentaire' => 'nullable|string|max:500',
            'commentaire_autre_entite' => 'nullable|string|max:500',
            'new_comment' => 'nullable|string|max:500',
            'action_commentaire' => 'nullable|string|max:500',
            'entite' => 'nullable|string|max:255',
            'entite_id' => 'nullable|integer|exists:entites,id',
            'piece_jointe' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt|max:10240',
            'impact_id' => 'nullable|exists:impacts,id',
            'type_action' => 'nullable|string|in:texte_libre,demande_validation,aviser,informer',
            'message_personnalise' => 'nullable|string|max:500',
            'destinataires' => 'nullable',
            'destinataires_metadata' => 'nullable',
            'actions' => 'nullable',
            'commentaires' => 'nullable',
            'avis_srcof' => 'nullable|string|max:1000',
            'visa_encadrant' => 'nullable|string|max:255',
        ];


        // Règles spécifiques pour CM
        if ($entiteCode === 'CM') {
            $rules['heure_appel_intervenant'] = 'nullable|date_format:Y-m-d\TH:i';
            $rules['heure_arrive_intervenant'] = 'nullable|date_format:Y-m-d\TH:i';
        }

        $validated = $request->validate($rules);

            Log::info('Après validation redacteur:', ['redacteur' => $validatedData['redacteur'] ?? 'NON DÉFINI']);

        Log::info('=== DEBUG STORE COMPLET ===');
        Log::info('Request data brutes:', ['data' => $request->all()]);
        Log::info('Type_action:', ['type_action' => $request->type_action]);
        Log::info('Destinataires bruts:', ['destinataires' => $request->destinataires]);

        $actions = [];
        if ($request->has('actions')) {
            $actionsData = $request->input('actions');
            Log::info('Actions brutes reçues:', ['actions' => $actionsData, 'type' => gettype($actionsData)]);

            if (is_string($actionsData)) {
                $actions = json_decode($actionsData, true) ?? [];
                Log::info('Actions après json_decode:', ['actions' => $actions]);
            } elseif (is_array($actionsData)) {
                $actions = $actionsData;
            }
        }

        //  Désérialisation des commentaires avec gestion d'erreurs
        $commentaires = [];
        if ($request->has('commentaires')) {
            $commentData = $request->input('commentaires');
            Log::info('Commentaires bruts reçus:', ['commentaires' => $commentData, 'type' => gettype($commentData)]);

            if (is_string($commentData)) {
                $commentaires = json_decode($commentData, true) ?? [];
            } elseif (is_array($commentData)) {
                $commentaires = $commentData;
            }
            Log::info('Commentaires traités:', ['commentaires' => $commentaires]);
        }

        //  Création de l'événement avec tous les nouveaux champs
        $evenement = Evenement::create([
            'nature_evenement_id' => $validated['nature_evenement_id'],
            'location_id' => $validated['location_id'] ?? null,
            'location_description' => null,
            'description' => $validated['description'],
            'consequence_sur_pdt' => $validated['consequence_sur_pdt'] ?? null,
            'redacteur' => $validated['redacteur'] ?? (Auth::check() ? Auth::user()->prenom . ' ' . Auth::user()->nom : ''),
            'statut' => $validated['statut'],
            'date_cloture' => $validated['date_cloture'] ?? null,
            'confidentialite' => $validated['confidentialite'] ?? null,
            'date_evenement' => $validated['date_evenement'],
            'heure_appel_intervenant' => $validated['heure_appel_intervenant'] ?? null,
            'heure_arrive_intervenant' => $validated['heure_arrive_intervenant'] ?? null,
            'entite' => Auth::check() && Auth::user()->entite ? Auth::user()->entite->code : $validated['entite'],
            'entite_id' => Auth::check() && Auth::user()->entite ? Auth::user()->entite->id : $validated['entite_id'],
            'impact_id' => $request->impact_id ?? null,
             'commentaire_autre_entite' => $validated['commentaire_autre_entite'],
            'avis_srcof' => $validated['avis_srcof'] ?? null, //  Nouveau champ
            'visa_encadrant' => $validated['visa_encadrant'] ?? null, //  Nouveau champ
        ]);

        Log::info('Événement créé:', ['evenement_id' => $evenement->id]);

        //  Gestion de la pièce jointe
        if ($request->hasFile('piece_jointe')) {
            $path = $request->file('piece_jointe')->store('pieces_jointes', 'public');
            $evenement->piece_jointe = $path;
            $evenement->type_piece_jointe = Str::startsWith($request->file('piece_jointe')->getMimeType(), 'image/') ? 'Image' : 'Fichier';
            $evenement->save();
            Log::info('Pièce jointe ajoutée:', ['path' => $path]);
        }

        //  Traitement du type_action individuel EN PREMIER
       if ($request->filled('type_action')) {
            Log::info('=== TRAITEMENT TYPE_ACTION INDIVIDUEL ===');

            $destinatairesMetadata = [];
            if ($request->has('destinataires')) {
                if (is_string($request->destinataires)) {
                    $destinatairesMetadata = json_decode($request->destinataires, true) ?? [];
                } elseif (is_array($request->destinataires)) {
                    $destinatairesMetadata = $request->destinataires;
                }
            } elseif ($request->has('destinataires_metadata')) {
                if (is_string($request->destinataires_metadata)) {
                    $destinatairesMetadata = json_decode($request->destinataires_metadata, true) ?? [];
                } elseif (is_array($request->destinataires_metadata)) {
                    $destinatairesMetadata = $request->destinataires_metadata;
                }
            }

            Log::info('destinataires type_action:', ['destinataires' => $destinatairesMetadata]);

            if (!empty($destinatairesMetadata)) {
                //  Créer le commentaire avec les noms des destinataires
                $nomsDestinataires = $this->getDestinataireNames($destinatairesMetadata);
                $destinatairesStr = implode(', ', $nomsDestinataires);

                $actionCommentaire = '';
                $messagePersonnalise = $request->message_personnalise ?? '';

                switch ($request->type_action) {
                    case 'demande_validation':
                        $actionCommentaire = "Demande de validation envoyée à : {$destinatairesStr}";
                        if ($messagePersonnalise) {
                            $actionCommentaire .= "\nMessage : {$messagePersonnalise}";
                        }
                        break;
                    case 'aviser':
                        $actionCommentaire = "Avis envoyé à : {$destinatairesStr}";
                        if ($messagePersonnalise) {
                            $actionCommentaire .= "\nMessage : {$messagePersonnalise}";
                        }
                        break;
                    case 'informer':
                        $actionCommentaire = "Information envoyée à : {$destinatairesStr}";
                        if ($messagePersonnalise) {
                            $actionCommentaire .= "\nMessage : {$messagePersonnalise}";
                        }
                        break;
                    case 'texte_libre':
                        $actionCommentaire = $messagePersonnalise ?: 'Action libre';
                        break;
                    default:
                        $actionCommentaire = $messagePersonnalise ?: 'Action effectuée';
                }

                $newAction = Action::create([
                    'evenement_id' => $evenement->id,
                    'commentaire' => $actionCommentaire,
                    'message' => $messagePersonnalise,
                    'type' => $request->type_action,
                    'auteur_id' => auth()->id(),
                    'destinataires_metadata' => json_encode($destinatairesMetadata),
                    'statut' => 'en_attente'
                ]);

                Action_user::create([
                    'action_id' => $newAction->id,
                    'user_id' => auth()->id(),
                ]);

                //  Envoi des emails
                if (in_array($request->type_action, ['demande_validation', 'aviser', 'informer'])) {
                    try {
                        $emails = $this->getDestinataireEmails($destinatairesMetadata);
                        Log::info('Emails récupérés pour type_action:', ['emails' => $emails]);

                        if (!empty($emails)) {
                            $this->sendActionEmails($newAction, $emails, $messagePersonnalise, $request->type_action);
                            Log::info('Emails type_action envoyés avec succès');
                        }
                    } catch (\Exception $e) {
                        Log::error("❌ Erreur envoi emails type_action: " . $e->getMessage());
                    }
                }

                Log::info('Action individuelle créée:', [
                    'action_id' => $newAction->id,
                    'commentaire' => $actionCommentaire,
                    'type' => $request->type_action,
                    'message' => $messagePersonnalise
                ]);
            }
        }

        //  CORRECTION : Traitement des actions en tableau SEULEMENT si pas de type_action individuel
        if (!$request->filled('type_action') && !empty($actions)) {
            Log::info('=== TRAITEMENT DES ACTIONS TABLEAU ===');
            Log::info('Nombre d\'actions à traiter:', ['count' => count($actions)]);

            foreach ($actions as $actionIndex => $action) {
                Log::info("Traitement action #{$actionIndex}:", ['action' => $action]);

                //  Extraction des destinataires
                $destinatairesMetadata = $action['destinataires_metadata'] ?? $action['destinataires'] ?? [];

                if (is_string($destinatairesMetadata)) {
                    $destinatairesMetadata = json_decode($destinatairesMetadata, true) ?? [];
                }

                Log::info('Destinataires extraits pour action:', ['destinataires' => $destinatairesMetadata]);

                //  Créer le commentaire d'action AVEC les noms des destinataires
                $actionCommentaire = '';
                $actionType = $action['type'] ?? '';
                $actionMessage = $action['message'] ?? '';

                if (!empty($destinatairesMetadata)) {
                    $nomsDestinataires = $this->getDestinataireNames($destinatairesMetadata);
                    $destinatairesStr = implode(', ', $nomsDestinataires);

                    switch ($actionType) {
                        case 'demande_validation':
                            $actionCommentaire = "Demande de validation envoyée à : {$destinatairesStr}";
                            if (!empty($actionMessage)) {
                                $actionCommentaire .= "\nMessage : {$actionMessage}";
                            }
                            break;
                        case 'aviser':
                            $actionCommentaire = "Avis envoyé à : {$destinatairesStr}";
                            if (!empty($actionMessage)) {
                                $actionCommentaire .= "\nMessage : {$actionMessage}";
                            }
                            break;
                        case 'informer':
                            $actionCommentaire = "Information envoyée à : {$destinatairesStr}";
                            if (!empty($actionMessage)) {
                                $actionCommentaire .= "\nMessage : {$actionMessage}";
                            }
                            break;
                        case 'texte_libre':
                            $actionCommentaire = $actionMessage ?: 'Action libre';
                            break;
                        default:
                            $actionCommentaire = $actionMessage ?: $action['commentaire'] ?? 'Action effectuée';
                            break;
                    }
                } else {
                    $actionCommentaire = $action['commentaire'] ?? $actionMessage ?: 'Action sans destinataire';
                }

                //  Création de l'action avec le bon commentaire
                $newAction = Action::create([
                    'evenement_id' => $evenement->id,
                    'commentaire' => $actionCommentaire, //  Commentaire avec destinataires
                    'message' => $actionMessage,
                    'type' => $actionType,
                    'auteur_id' => $action['auteur_id'] ?? auth()->id(),
                    'destinataires_metadata' => json_encode($destinatairesMetadata),
                    'statut' => 'en_attente',
                    'created_at' => $action['created_at'] ?? now()
                ]);

                Log::info('Action créée avec commentaire:', [
                    'action_id' => $newAction->id,
                    'commentaire' => $actionCommentaire,
                    'type' => $actionType,
                    'message' => $actionMessage
                ]);

                // Création de la relation Action_user
                Action_user::create([
                    'action_id' => $newAction->id,
                    'user_id' => $action['auteur_id'] ?? auth()->id(),
                ]);

                //  Envoi d'emails
                if (in_array($actionType, ['demande_validation', 'aviser', 'informer']) && !empty($destinatairesMetadata)) {
                    Log::info('Traitement des emails pour action:', ['type' => $actionType]);

                    try {
                        $emails = $this->getDestinataireEmails($destinatairesMetadata);
                        Log::info('Emails récupérés:', ['emails' => $emails]);

                        if (!empty($emails)) {
                            $this->sendActionEmails($newAction, $emails, $actionMessage, $actionType);
                            Log::info(" Emails envoyés pour l'action {$newAction->id}");
                        } else {
                            Log::warning('Aucun email trouvé - pas d\'envoi');
                        }
                    } catch (\Exception $e) {
                        Log::error("❌ Erreur envoi emails pour action {$newAction->id}: " . $e->getMessage());
                        // L'erreur d'email n'empêche pas la création de l'événement
                    }
                } else {
                    Log::info('Pas d\'envoi d\'emails nécessaire pour ce type d\'action');
                }
            }
        } else if ($request->filled('type_action')) {
            Log::info('=== ACTIONS TABLEAU IGNORÉES car type_action individuel présent ===');
        } else {
            Log::info('=== AUCUNE ACTION À TRAITER ===');
        }

        //  Création des commentaires (tous de type 'simple')
        Log::info('=== TRAITEMENT DES COMMENTAIRES ===');
        foreach ($commentaires as $comment) {
            Commentaire::create([
                'evenement_id' => $evenement->id,
                'redacteur' => $comment['redacteur'] ?? $evenement->redacteur,
                'text' => $comment['text'] ?? '',
                'type' => 'simple', //  Tous les commentaires sont 'simple'
                'date' => $comment['date'] ?? now(),
            ]);
            Log::info('Commentaire créé:', ['text' => substr($comment['text'] ?? '', 0, 50)]);
        }

        //  Création du commentaire principal
        if (!empty($validated['commentaire'])) {
            Commentaire::create([
                'evenement_id' => $evenement->id,
                'redacteur' => $evenement->redacteur,
                'text' => $validated['commentaire'],
                'type' => 'simple',
                'date' => now(),
            ]);
            Log::info('Commentaire principal créé');
        }

        //  Création du nouveau commentaire si fourni
        if (!empty($validated['new_comment'])) {
            Commentaire::create([
                'evenement_id' => $evenement->id,
                'redacteur' => $evenement->redacteur,
                'text' => $validated['new_comment'],
                'type' => 'simple',
                'date' => now(),
            ]);
            Log::info('Nouveau commentaire créé');
        }

        //  Associations avec gestion d'erreurs
        if (!empty($validated['location_id'])) {
            $location = Location::find($validated['location_id']);
            if ($location) {
                $evenement->location()->associate($location);
                $evenement->save();
                Log::info('Location associée:', ['location_id' => $location->id]);
            }
        }

        if (!empty($validated['nature_evenement_id'])) {
            $nature_evenement = Nature_evenement::find($validated['nature_evenement_id']);
            if ($nature_evenement) {
                $evenement->nature_evenement()->associate($nature_evenement);
                $evenement->save();
                Log::info('Nature événement associée:', ['nature_id' => $nature_evenement->id]);
            }
        }

        if (!empty($validated['impact_id'])) {
            $evenement->impacts()->sync([$validated['impact_id']]);
            Log::info('Impact associé:', ['impact_id' => $validated['impact_id']]);
        }

        //  Chargement des relations pour la réponse
        $evenement->load([
            'location',
            'nature_evenement',
            'impact',
            'actions.auteur',
            'commentaires',
            'entite'
        ]);

        Log::info(' Événement créé avec succès:', ['evenement_id' => $evenement->id]);

        //  Réponse unifiée
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Événement créé avec succès !',
                'evenement' => $evenement
            ], 201);
        }

        return redirect()->route('evenements.index')->with('success', 'Événement créé avec succès.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::info('❌ Erreurs de validation:', $e->errors());

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
        return redirect()->back()->withErrors($e->errors())->withInput();

    } catch (\Exception $e) {
        Log::error('❌ Erreur lors de la création:', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ], 500);
        }
        return redirect()->back()->with('error', 'Erreur lors de la création: ' . $e->getMessage());
    }
}
private function sendActionEmails($action, $emails, $message, $type)
{
    Log::info('Envoi d\'emails à :', $emails);

    try {
        foreach ($emails as $email) {
            $user = User::where('email', $email)->first();

            if ($user) {
                Mail::to($email)->send(new ActionNotificationToUser($action, $message, $type, $user));
                Log::info("Email envoyé à l'utilisateur: {$user->prenom} {$user->nom} ({$email})");
            } else {
                Mail::to($email)->send(new ActionNotificationGeneric($action, $message, $type, $email));
                Log::info("Email envoyé à l'adresse: {$email}");
            }
        }
    } catch (\Exception $e) {
        Log::error("Erreur lors de l'envoi des emails d'action: " . $e->getMessage());
    }
}

private function getDestinataireEmails($destinatairesMetadata)
{ Log::info('=== DEBUG getDestinataireEmails AMÉLIORÉ ===');
    Log::info('Paramètre brut reçu:', ['data' => $destinatairesMetadata, 'type' => gettype($destinatairesMetadata)]);

    $emails = [];

    // Vérification que le paramètre est bien un array
    if (!is_array($destinatairesMetadata)) {
        Log::warning('destinatairesMetadata n\'est pas un array', [
            'type_recu' => gettype($destinatairesMetadata),
            'valeur' => $destinatairesMetadata
        ]);
        return $emails;
    }

    // Vérification que l'array n'est pas vide
    if (empty($destinatairesMetadata)) {
        Log::info('destinatairesMetadata est vide');
        return $emails;
    }

    foreach ($destinatairesMetadata as $index => $destinataireId) {
        Log::info("Traitement destinataire $index:", ['destinataireId' => $destinataireId]);

        try {
            // Vérification que destinataireId est une string
            if (!is_string($destinataireId)) {
                Log::warning("Destinataire $index n'est pas une string", [
                    'type' => gettype($destinataireId),
                    'valeur' => $destinataireId
                ]);
                continue;
            }

            // Traitement des listes de diffusion
            if (str_starts_with($destinataireId, 'liste_')) {
                $listeId = str_replace('liste_', '', $destinataireId);
                Log::info("Recherche liste de diffusion:", ['listeId' => $listeId]);

                $liste = Liste_diffusion::with('users')->find($listeId);

                if ($liste) {
                    Log::info('Liste de diffusion trouvée:', [
                        'id' => $liste->id,
                        'nom' => $liste->nom,
                        'users_count' => $liste->users ? $liste->users->count() : 0
                    ]);

                    if ($liste->users && $liste->users->count() > 0) {
                        foreach ($liste->users as $userIndex => $user) {
                            if ($user && $user->email) {
                                $emails[] = $user->email;
                                Log::info("Email ajouté depuis liste:", [
                                    'user_id' => $user->id,
                                    'email' => $user->email,
                                    'liste' => $liste->nom
                                ]);
                            } else {
                                Log::warning("Utilisateur sans email dans la liste:", [
                                    'user_id' => $user ? $user->id : 'null',
                                    'liste' => $liste->nom
                                ]);
                            }
                        }
                    } else {
                        Log::warning('Liste de diffusion sans utilisateurs:', ['liste' => $liste->nom]);
                    }
                } else {
                    Log::warning('Liste de diffusion non trouvée:', ['listeId' => $listeId]);
                }
            }
            // Traitement des utilisateurs individuels
            elseif (str_starts_with($destinataireId, 'user_')) {
                $userId = str_replace('user_', '', $destinataireId);
                Log::info("Recherche utilisateur:", ['userId' => $userId]);

                $user = User::find($userId);

                if ($user) {
                    if ($user->email) {
                        $emails[] = $user->email;
                        Log::info("Email ajouté depuis utilisateur:", [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'nom' => $user->name ?? 'N/A'
                        ]);
                    } else {
                        Log::warning('Utilisateur sans email:', [
                            'user_id' => $user->id,
                            'nom' => $user->name ?? 'N/A'
                        ]);
                    }
                } else {
                    Log::warning('Utilisateur non trouvé:', ['userId' => $userId]);
                }
            } else {
                Log::warning('Format de destinataire non reconnu:', [
                    'destinataireId' => $destinataireId,
                    'index' => $index
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du destinataire:', [
                'destinataireId' => $destinataireId,
                'index' => $index,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    // Suppression des doublons
    $emails = array_unique($emails);

    Log::info('Emails finaux extraits:', [
        'emails' => $emails,
        'count' => count($emails)
    ]);

    Log::info('=== FIN DEBUG getDestinataireEmails ===');

    return $emails;
}
/**
 * Récupère les détails des destinataires à partir de leurs identifiants
 *
 * @param array $destinatairesMetadata Tableau d'identifiants (liste_X ou user_X)
 * @return array Détails des destinataires
 */
private function getDestinataireDetails($destinatairesMetadata)
{
    $details = [];

    foreach ($destinatairesMetadata as $destinataireId) {
        if (str_starts_with($destinataireId, 'liste_')) {
            // C'est une liste de diffusion
            $listeId = str_replace('liste_', '', $destinataireId);
            $liste = Liste_diffusion::with('users')->find($listeId);

            if ($liste) {
                $details[] = [
                    'type' => 'liste',
                    'id' => $liste->id,
                    'nom' => $liste->nom,
                    'users_count' => $liste->users->count(),
                    'users' => $liste->users->map(function($user) {
                        return [
                            'id' => $user->id,
                            'nom' => $user->prenom . ' ' . $user->nom,
                            'email' => $user->email
                        ];
                    })
                ];
            }
        } elseif (str_starts_with($destinataireId, 'user_')) {
            // C'est un utilisateur individuel
            $userId = str_replace('user_', '', $destinataireId);
            $utilisateur = User::find($userId);

            if ($utilisateur) {
                $details[] = [
                    'type' => 'utilisateur',
                    'id' => $utilisateur->id,
                    'nom' => $utilisateur->prenom . ' ' . $utilisateur->nom,
                    'email' => $utilisateur->email,
                    'entite' => $utilisateur->entite ? $utilisateur->entite->nom : null
                ];
            }
        }
    }

    return $details;
}
    public function edit($id)
    {
        // Logique pour afficher le formulaire d'édition d'un événement
        // Récupération de l'événement par son ID
        $evenement = Evenement::findOrFail($id);
        return view('evenement.edit', compact('evenement'));
    }
public function update(Request $request, $id)
{
    try {
        $evenement = Evenement::findOrFail($id);
      Log::info('=== DEBUG UPDATE COMPLET ===');
        Log::info('Request data brutes:', ['data' => $request->all()]);


          if ($request->has('impact_id')) {
            $evenement->impact_id = $request->impact_id;
            Log::info(' Impact ID assigné:', ['impact_id' => $request->impact_id]);
        }

        if ($request->has('type_action') && in_array($request->type_action, ['demande_validation', 'aviser', 'informer'])) {
            Log::info('Action détectée:', [
                'type' => $request->type_action,
                'destinataires_présents' => $request->has('destinataires'),
                'destinataires_type' => gettype($request->destinataires),
                'destinataires_contenu' => $request->destinataires
            ]);
            if (is_string($request->destinataires)) {
                $request->merge([
                    'destinataires' => json_decode($request->destinataires, true)
                ]);
                Log::info('Destinataires après décodage:', ['destinataires' => $request->destinataires]);
            }
        }
        // Validation des champs
        $rules = [
            'nature_evenement_id' => 'sometimes|exists:nature_evenements,id',
            'location_id' => 'sometimes|nullable|integer|exists:locations,id',
            'description' => 'sometimes|nullable|string',
            'consequence_sur_pdt' => 'sometimes|nullable|boolean',
            'redacteur' => 'sometimes|nullable|string|max:255',
            'statut' => 'sometimes|nullable|string|in:en_cours,cloture,archive',
            'date_cloture' => 'sometimes|nullable|date',
            'confidentialite' => 'sometimes|nullable|boolean',
            'date_evenement' => 'sometimes|nullable|date_format:Y-m-d\TH:i',
            'type_action' => 'sometimes|nullable|string|in:texte_libre,demande_validation,aviser,informer',
            'message_personnalise' => 'sometimes|string|nullable',
            'destinataires_metadata' => 'sometimes|array',
            'destinataires' => 'sometimes|array',
            'new_comment' => 'sometimes|nullable|string|max:500',
            'commentaire_autre_entite' => 'nullable|string|max:2000',
            'avis_srcof' => 'sometimes|nullable|string|max:1000',
            'visa_encadrant' => 'sometimes|nullable|string|max:255',
            'actions' => 'sometimes|nullable',
            'commentaires' => 'sometimes|nullable',
            'piece_jointe' => 'sometimes|nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt|max:10240',
        ];

        // Désérialisation des champs JSON envoyés par FormData
        if ($request->has('actions')) {
            if (is_string($request->actions)) {
                $request->merge([
                    'actions' => json_decode($request->actions, true) ?? []
                ]);
            }
        }

        if ($request->has('commentaires') && is_string($request->commentaires)) {
            $request->merge([
                'commentaires' => json_decode($request->commentaires, true) ?? []
            ]);
        }

        if ($request->has('new_comment') && is_string($request->new_comment)) {
            $request->merge([
                'new_comment' => json_decode($request->new_comment, true) ?? []
            ]);
        }

        $validated = $request->validate($rules);

        // Mise à jour des champs de l'événement
        $evenementFields = array_intersect_key($validated, array_flip([
            'nature_evenement_id', 'location_id', 'description', 'consequence_sur_pdt',
            'redacteur', 'statut', 'date_cloture', 'confidentialite', 'date_evenement','visa_encadrant',
            'impact_id', 'avis_srcof', 'commentaire_autre_entite', 'heure_appel_intervenant',
        ]));

        if (!empty($evenementFields)) {
            $evenement->update($evenementFields);
            Log::info('Champs événement mis à jour:', ['fields' => $evenementFields]);
        }

        // Gestion de la pièce jointe (mise à jour)
        if ($request->hasFile('piece_jointe')) {
            if ($evenement->piece_jointe) {
                \Storage::disk('public')->delete($evenement->piece_jointe);
            }
            $path = $request->file('piece_jointe')->store('pieces_jointes', 'public');
            $evenement->piece_jointe = $path;
            $evenement->type_piece_jointe = Str::startsWith($request->file('piece_jointe')->getMimeType(), 'image/') ? 'Image' : 'Fichier';
            $evenement->save();
            Log::info('Pièce jointe mise à jour:', ['path' => $path]);
        }

        // Ajout des nouveaux commentaires via commentaires (tableau)
        if ($request->has('commentaires') && is_array($request->commentaires)) {
            foreach ($request->commentaires as $comment) {
                // Créer seulement si pas d'id ou id temporaire
                if (!isset($comment['id']) || $comment['id'] > 1000000000000) {
                    Log::info('Type du commentaire: ' . gettype($comment));
                    Log::info('Commentaire brut:', ['comment' => $comment]);

                    // Validation et nettoyage des données
                    $commentData = [
                        'evenement_id' => $evenement->id,
                        'redacteur' => isset($comment['redacteur']) ? $comment['redacteur'] : $evenement->redacteur,
                        'text' => isset($comment['text']) ? $comment['text'] : '',
                        'type' => 'simple',
                        'date' => isset($comment['date']) ? $comment['date'] : now(),
                    ];

                    Log::info('Données commentaire à insérer:', ['commentData' => $commentData]);

                    try {
                        Commentaire::create($commentData);
                        Log::info('Commentaire créé avec succès');
                    } catch (\Exception $e) {
                        Log::error('Erreur lors de la création du commentaire: ' . $e->getMessage());
                    }
                }
            }
        }

        // // Ajout du commentaire "Avis SRCOF" si présent
        // if (!empty($validated['avis_srcof'])) {
        //     Commentaire::create([
        //         'evenement_id' => $evenement->id,
        //         'redacteur' => Auth::user()->nom_complet ?? $evenement->redacteur,
        //         'text' => $validated['avis_srcof'],
        //         'type' => 'avis_srcof',
        //         'date' => now(),
        //     ]);
        //     Log::info('Avis SRCOF ajouté');
        // }

        // Ajout des actions (tableau) - VERSION UNIQUE
        if ($request->has('actions') && is_array($request->actions)) {
           foreach ($request->actions as $actionData) {
    Log::info('=== DEBUG ACTION UPDATE ===');
    Log::info('Action data complète:', ['actionData' => $actionData]);
    Log::info('Clés disponibles:', ['keys' => array_keys($actionData)]);

    // Vérifier si c'est une nouvelle action (pas d'ID ou ID temporaire)
    if (!isset($actionData['id']) || $actionData['id'] > 1000000000000) {

        // CORRECTION ICI : Chercher les destinataires au bon endroit
        $destinatairesMetadata = $actionData['destinataires_metadata'] ?? $actionData['destinataires'] ?? [];

        Log::info('destinataires extraits:', ['destinataires' => $destinatairesMetadata]);
        Log::info('Type des destinataires:', ['type' => gettype($destinatairesMetadata)]);

        $newAction = Action::create([
            'evenement_id' => $evenement->id,
            'commentaire' => $actionData['commentaire'] ?? '',
            'message' => $actionData['message'] ?? '',
            'type' => $actionData['type'] ?? '',
            'auteur_id' => $actionData['auteur_id'] ?? auth()->id(),
            'destinataires_metadata' => json_encode($destinatairesMetadata),
            'created_at' => $actionData['created_at'] ?? now()
        ]);
                    Log::info('Avant getDestinataireEmails:', ['destinataires' => $destinatairesMetadata]);
                    $emails = $this->getDestinataireEmails($destinatairesMetadata);

                    Log::info('Emails récupérés:', ['emails' => $emails]);

                    if (!empty($emails)) {
                        $this->sendActionEmails($newAction, $emails, $actionData['message'] ?? '', $actionData['type'] ?? '');
                        Log::info('Emails envoyés avec succès');
                    } else {
                        Log::warning('Aucun email trouvé - pas d\'envoi');
                    }
                } else {
                    Log::info('Action existante ignorée', ['id' => $actionData['id']]);
                }
            }
        }

        // Traitement du type_action individuel (si pas d'actions en tableau)
        if ($request->filled('type_action') && (!$request->has('actions') || empty($request->actions))) {
            Log::info('=== DEBUG TYPE_ACTION INDIVIDUEL ===');

            $destinatairesMetadata = $request->input('destinataires', []);
            Log::info('destinataires type_action:', ['destinataires' => $destinatairesMetadata]);

            if (!empty($destinatairesMetadata)) {
                $actionCommentaire = '';
                switch ($request->type_action) {
                    case 'demande_validation':
                        $actionCommentaire = 'Demande de validation envoyée';
                        break;
                    case 'aviser':
                        $actionCommentaire = 'Destinataires avisés';
                        break;
                    case 'informer':
                        $actionCommentaire = 'Destinataires informés';
                        break;
                    default:
                        $actionCommentaire = $request->message_personnalise ?? 'Action effectuée';
                }

                $newAction = Action::create([
                    'evenement_id' => $evenement->id,
                    'commentaire' => $actionCommentaire,
                    'message' => $request->message_personnalise ?? '',
                    'type' => $request->type_action,
                    'auteur_id' => auth()->id(),
                    'destinataires_metadata' => json_encode($destinatairesMetadata),
                ]);

                Action_user::create([
                    'action_id' => $newAction->id,
                    'user_id' => auth()->id(),
                ]);

                $emails = $this->getDestinataireEmails($destinatairesMetadata);

                if (!empty($emails)) {
                    $this->sendActionEmails($newAction, $emails, $request->message_personnalise ?? '', $request->type_action);
                    Log::info('Email type_action envoyé avec succès');
                }
            }
        }

        // Charger les relations pour retourner l'événement complet
        $evenement->load([
            'location', 'nature_evenement',
             'impact', 'actions.auteur',
              'commentaires',
               'entite']);

        Log::info('Événement mis à jour avec succès:', ['evenement_id' => $evenement->id]);

        // Réponse JSON ou redirection
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Événement mis à jour avec succès',
                'evenement' => $evenement
            ]);
        }

        return redirect()->route('evenements.index')->with('success', 'Événement mis à jour avec succès.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::info('Erreurs de validation:', ['errors' => $e->errors()]);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
        return redirect()->back()->withErrors($e->errors())->withInput();

    } catch (\Exception $e) {
        Log::error('Erreur lors de la mise à jour: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    }
}
public function diffuserEvenement(Request $request, Evenement $evenement)
{
    try {
        $request->validate([
            'destinataires' => 'required|array|min:1',
            'destinataires.*' => 'required|string',
            'message_personnalise' => 'nullable|string',
            'inclure_actions' => 'boolean',
            'inclure_commentaires' => 'boolean'
        ]);

        $destinataires = $request->destinataires;
        $messagePersonnalise = $request->message_personnalise;
        $inclureActions = $request->inclure_actions ?? true;
        $inclureCommentaires = $request->inclure_commentaires ?? true;

        // Collecter tous les emails destinataires
        $emails = [];
        $destinatairesNoms = [];

        foreach ($destinataires as $destinataireId) {
            if (str_starts_with($destinataireId, 'liste_')) {
                // C'est une liste de diffusion
                $listeId = str_replace('liste_', '', $destinataireId);
                $liste = \App\Models\Liste_diffusion::with('users')->find($listeId);

                if ($liste) {
                    $destinatairesNoms[] = "📋 {$liste->nom}";
                    foreach ($liste->users as $user) {
                        if ($user->email && !in_array($user->email, $emails)) {
                            $emails[] = $user->email;
                        }
                    }
                }
            } elseif (str_starts_with($destinataireId, 'user_')) {
                // C'est un utilisateur individuel
                $userId = str_replace('user_', '', $destinataireId);
                $user = \App\Models\User::find($userId);

                if ($user && $user->email) {
                    $destinatairesNoms[] = "👤 {$user->prenom} {$user->nom}";
                    if (!in_array($user->email, $emails)) {
                        $emails[] = $user->email;
                    }
                }
            }
        }

        if (empty($emails)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun email valide trouvé dans les destinataires'
            ], 400);
        }

        // Charger l'événement avec ses relations
        $evenement->load([
            'location',
            'nature_evenement',
            'impact',
            'entite',
            'actions' => function($query) use ($inclureActions) {
                if ($inclureActions) {
                    $query->with('auteur')->orderBy('created_at', 'desc');
                }
            },
            'commentaires' => function($query) use ($inclureCommentaires) {
                if ($inclureCommentaires) {
                    $query->with('auteur')->orderBy('created_at', 'desc');
                }
            }
        ]);

        // Envoyer l'email à tous les destinataires
        foreach ($emails as $email) {
            Mail::to($email)->send(new EvenementDiffusion(
                $evenement,
                $messagePersonnalise,
                auth()->user(),
                $inclureActions,
                $inclureCommentaires
            ));
        }

        // Créer une action pour tracer la diffusion
        \App\Models\Action::create([
            'evenement_id' => $evenement->id,
            'type' => 'diffusion',
            'commentaire' => "Événement diffusé à " . implode(', ', $destinatairesNoms) . " (" . count($emails) . " emails)",
            'message' => $messagePersonnalise,
            'auteur_id' => auth()->id(),
            'destinataires_metadata' => json_encode([
                'destinataires' => $destinataires,
                'emails' => $emails,
                'noms' => $destinatairesNoms
            ])
        ]);

        Log::info('Événement diffusé avec succès', [
            'evenement_id' => $evenement->id,
            'nombre_emails' => count($emails),
            'destinataires' => $destinatairesNoms,
            'auteur' => auth()->user()->nom
        ]);

        return response()->json([
            'success' => true,
            'message' => "Événement diffusé avec succès à " . count($emails) . " destinataire(s)",
            'details' => [
                'nombre_emails' => count($emails),
                'destinataires' => $destinatairesNoms
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Erreur lors de la diffusion de l\'événement ' . $evenement->id . ': ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la diffusion : ' . $e->getMessage()
        ], 500);
    }
}
public function updateField(Request $request, $id)
{
    $evenement = \App\Models\Evenement::findOrFail($id);
    $field = $request->input('field');
    $value = $request->input('value');

    // Sécurise les champs modifiables
    $allowed = [
        'date_evenement', 'nature_evenement_id', 'location_id', 'description',
        'consequence_sur_pdt', 'redacteur', 'statut', 'location_description',
        'date_cloture', 'confidentialite', 'impact_id', 'heure_appel_intervenant',
        'heure_arrive_intervenant', 'commentaire', 'entite','avis_srcof', 'visa_encadrant', 'piece_jointe', 'type_piece_jointe'
    ];
    if (!in_array($field, $allowed)) {
        return response()->json(['error' => 'Champ non autorisé'], 403);
    }

    $evenement->$field = $value;
    $evenement->save();

    return response()->json(['success' => true]);
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
    public function show($id)
    {
        // Logique pour afficher les détails d'un événement
        $evenement = Evenement::findOrFail($id);
        return view('evenement.show', compact('evenement'));
    }
    public function search(Request $request)
    {
        // Logique pour rechercher des événements
        $query = $request->input('query');
        $evenements = Evenement::where('titre', 'like', '%' . $query . '%')->get();

        return view('evenement.index', compact('evenements'));
    }

    public function addComment(Request $request, $id)
    {
        // Logique pour ajouter un commentaire à un événement
        $validated = $request->validate([
            'commentaire' => 'required|string|max:500',
        ]);

        // Récupération de l'événement par son ID
        $evenement = Evenement::findOrFail($id);

        // Ajout du commentaire à l'événement
        $evenement->comments()->create([
            'commentaire' => $request->commentaire,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('evenements.show', $id)->with('success', 'Commentaire ajouté avec succès.');
    }
    public function addAction(Request $request, $id)
    {
        // Logique pour ajouter une action à un événement
        $validated = $request->validate([
            'action' => 'required|string|max:255',
        ]);

        // Récupération de l'événement par son ID
        $evenement = Evenement::findOrFail($id);

        // Ajout de l'action à l'événement
        $evenement->actions()->create([
            'action' => $request->action,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('evenements.show', $id)->with('success', 'Action ajoutée avec succès.');
    }
public function diffuse(Request $request, $id)
{
    // Logique pour diffuser un événement
    $evenement = Evenement::findOrFail($id);

    // Logique de diffusion (par exemple, envoi d'email, notification, etc.)
    // ...

    return redirect()->route('evenements.show', $id)->with('success', 'Événement diffusé avec succès.');

}

public function archive(Request $request, $id)
{
    // Logique pour archiver un événement
    $evenement = Evenement::findOrFail($id);
    $evenement->archived = true; // Supposons que vous ayez un champ 'archived' dans votre modèle
    $evenement->save();

    return redirect()->route('evenements.index')->with('success', 'Événement archivé avec succès.');
}
    public function unarchive(Request $request, $id)
    {
        // Logique pour désarchiver un événement
        $evenement = Evenement::findOrFail($id);
        $evenement->archived = false; // Supposons que vous ayez un champ 'archived' dans votre modèle
        $evenement->save();

        return redirect()->route('evenements.index')->with('success', 'Événement désarchivé avec succès.');
    }
    public function showArchived()
    {
        // Logique pour afficher les événements archivés
        $evenements = Evenement::where('archived', true)->get();
        return view('evenement.archived', compact('evenements'));
    }
    public function tagEntite(Request $request, $id)
    {
        // Logique pour ajouter un tag à un événement
        $validated = $request->validate([
            'tag' => 'required|string|max:50',
        ]);

        // Récupération de l'événement par son ID
        $evenement = Evenement::findOrFail($id);

        // Ajout du tag à l'événement
        $evenement->tags()->attach($request->tag);

        return redirect()->route('evenements.show', $id)->with('success', 'Tag ajouté avec succès.');
    }
    public function getAvisSrcof($evenementId)
{
    $evenement = Evenement::findOrFail($evenementId);

    $avisSrcof = Commentaire::where('evenement_id', $evenementId)
        ->where('type', 'avis_srcof')
        ->latest()
        ->first();

    return response()->json([
        'avis_srcof' => $avisSrcof ? $avisSrcof->text : null,
        'redacteur' => $avisSrcof ? $avisSrcof->redacteur : null,
        'date' => $avisSrcof ? $avisSrcof->created_at : null
    ]);
}

public function json()
{
    return response()->json(
        Evenement::with([
        'nature_evenement',
        'location',
        'impact',
        'entite',
        'actions',
        'commentaires'
    ])->get()
    );
}
}


