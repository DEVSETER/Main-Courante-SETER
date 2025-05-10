<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Action_user extends Model
{
    use HasFactory;
    protected $fillable = [
        'action_id',
        'user_id',
        'type_action_user'
    ];

}
