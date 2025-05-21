<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Role extends Model
{
       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    // Role.php

  public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }    public function users()
{
    return $this->hasMany(User::class);
}
}
