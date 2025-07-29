<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'evenement_id',
        'redacteur',
        'text',
        'type',
        'date',
    ];
      protected $casts = [
        'date' => 'datetime',
    ];
   protected $appends = ['auteur_nom_complet'];
    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'evenement_id');
    }
      public function auteur()
    {
        return $this->belongsTo(User::class, 'redacteur', 'id');
    }
     public function getAuteurNomCompletAttribute()
    {
        if ($this->auteur) {
            return $this->auteur->prenom . ' ' . $this->auteur->nom;
        }
        return 'Utilisateur inconnu';
    }
}
