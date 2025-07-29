<?php

namespace App\Models;

// use App\Models\Action;
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

    public function action()
{
    return $this->belongsTo(Action::class, 'action_id');
}
}
