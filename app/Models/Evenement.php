<?php

namespace App\Models;

use App\Models\User;
use App\Models\Action;
use App\Models\Entite;
use App\Models\Impact;
use App\Models\Location;
use App\Models\Commentaire;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nature_evenement_id',
        'date_evenement',
        'location_id',
        'location_description',
        'description',
        'consequence_sur_pdt',
        'redacteur',
        'statut',
        'date_cloture',
        'confidentialite',
        'heure_appel_intervenant',
        'heure_arrive_intervenant',
        'entite',
        'entite_id',
        'piece_jointe',
        'impact_id',
        'avis_srcof',
        'visa_encadrant',
        'impact_id',
    ];

 
protected static function booted()
{
    /*
    static::created(function ($evenement) {
        // 1️⃣ Créer l'action par défaut
                $commentaire = $evenement->commentaire_action ?? '';

        $action = $evenement->actions()->create([
            'commentaire' => $commentaire,
            'auteur_id' => auth()->id() ?? 1,
        ]);

        // 2️⃣ Créer les liens ActionUser (par exemple pour l'auteur)
        // Ajoute d'autres user_id si besoin (ex : une liste d'intervenants)
        \App\Models\Action_user::create([
            'action_id' => $action->id,
            'user_id' => auth()->id() ?? 1,
            // Ajoute d'autres champs si nécessaire
        ]);
    });
    */
}



        public function nature_evenement()
        {
            return $this->belongsTo(Nature_evenement::class, 'nature_evenement_id');
        }
public function location()
{
    return $this->belongsTo(Location::class, 'location_id');
}


public function redacteur()
{
    return $this->belongsTo(User::class, 'redacteur'); // si 'redacteur' = user_id

}

public function commentaires()

   {
       return $this->hasMany(Commentaire::class, 'evenement_id');
   }
public function actions()
{
    return $this->hasMany(Action::class, 'evenement_id');
}
    public function entite()
{
    return $this->belongsTo(Entite::class, 'entite_id');
}

 public function impact()
    {
        return $this->belongsTo(Impact::class, 'impact_id');
    }

public function users()
{
    return $this->belongsToMany(User::class);
}


}
