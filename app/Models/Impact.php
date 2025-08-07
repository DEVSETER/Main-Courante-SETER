<?php

namespace App\Models;

use App\Models\Evenement;
use Illuminate\Database\Eloquent\Model;

class Impact extends Model
{
         /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',


    ];
    // public function evenements()
    // {
    //     return $this->belongsToMany(Evenement::class, 'evenement_impact', 'impact_id', 'evenement_id')
    //         ->withPivot('duree')
    //         ->withTimestamps();
    // }

}
