<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['libelle'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
