<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liste_diffusion extends Model
{
         /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'nom',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'liste_diffusion_user');
    }
}
