<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','user_invited_id','mobile'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function invitee()
    {
        return $this->belongsTo('App\Models\User','user_invited_id');
    }
}
