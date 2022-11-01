<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id','title','body','is_read','entity_type','type'
    ];

    static function createNotification($user_id,$title,$body,$type)
    {
        return Notification::create([
            'user_id' => $user_id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'entity_type' => 'user',
        ]);
    }
}
