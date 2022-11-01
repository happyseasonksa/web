<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactHistory extends Model
{
    use HasFactory;
    protected $fillable = [
            'user_id', 'admin_id', 'item_id', 'contact_type',
        ];

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }

}
