<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Entite extends Model
{

    protected $fillable = [

        'nom',
        'code'
    ];

    public function users()
{
    return $this->hasMany(User::class);
}
}
