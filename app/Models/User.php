<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Action;
use App\Models\Liste_diffusion;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,  HasFactory, Notifiable, HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'email',
        'password',
        'prenom',
        'role_id',
        'telephone',
        'direction',
        'fonction',
        'entite_id',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
{
    return $this->belongsTo(Role::class);
}

public function hasPermission($permission)
    {
        return $this->role->permissions->contains('linelle', $permission);
    }

public function listeDifusions()
    {
        return $this->belongsToMany(Liste_diffusion::class, 'liste_difusion_user');
    }

public function actions()
{
    return $this->hasMany(Action::class, 'auteur_id');
}

// Action ou evenement tagué par l'utilisateur
public function actionsTags()
{
    return $this->belongsToMany(Action::class, 'action_user');
}

public function entite()
{
    return $this->belongsTo(Entite::class);
}

}
