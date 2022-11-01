<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'admin_id','title','body','icon','is_read'
    ];

    static function createAdminNotification($admin,$title,$body,$icon='fas fa-info-circle')
    {   
        if (isset($admin->web_fcm_token)) {
            sendAdminPushNotification($admin->web_fcm_token,$title,$body);
        }
        return AdminNotification::create([
            'admin_id' => $admin->id,
            'title' => $title,
            'body' => $body,
            'icon' => $icon,
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }
}
