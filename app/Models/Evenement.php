<?php

namespace App\Models;

use App\Models\User;
use App\Models\Action;
use App\Models\Location;
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
    ];

    public function impacts()
    {
        return $this->belongsToMany(Impact::class, 'evenement_impact')
            ->withPivot('duree')
            ->withTimestamps();
    }

    public function natureEvenement()
{
    return $this->belongsTo(Nature_evenement::class); // à créer
}
public function location()
{
    return $this->belongsTo(Location::class); // à créer
}

public function redacteur()
{
    return $this->belongsTo(User::class, 'redacteur'); // si 'redacteur' = user_id

}

public function actions()
{
    return $this->hasMany(Action::class);
}

}
