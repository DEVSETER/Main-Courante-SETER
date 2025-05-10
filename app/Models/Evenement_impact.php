<?php

namespace App\Models;

use App\Models\Impact;
use App\Models\Evenement;
use Illuminate\Database\Eloquent\Model;

class Evenement_impact extends Model
{
         /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'evenement_id',
        'impact_id',
        'duree'

    ];

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    public function impact()
    {
        return $this->belongsTo(Impact::class);
    }
}
