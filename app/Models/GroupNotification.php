<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id', 'body'
    ];

    public function createNotify($data)
    {
        return GroupNotification::create([
            'admin_id'=>$data['admin_id'],
            'body'=>$data['body']
        ]);
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
}
