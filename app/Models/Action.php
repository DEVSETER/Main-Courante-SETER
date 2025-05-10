<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Action extends Model
{
    use HasFactory;
    protected $fillable = [
        'commentaire',
        'evenement_id',
        'auteur_id',
    ];
    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function tags()
    {
        return $this->belongsToMany(User::class, 'action_user');
    }
}
