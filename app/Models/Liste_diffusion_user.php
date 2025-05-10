<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liste_diffusion_user extends Model
{
          /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'user_id',
        'liste_diffusion_id',
    ];
}
