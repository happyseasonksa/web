<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemServices extends Model
{
    use HasFactory;
    protected $fillable= [
        'item_id', 'admin_id', 'service', 'service_type',
    ];

    public function createService($data)
    {

        return ItemServices::create([
            'item_id'=>$data['item_id'],
            'admin_id'=>$data['admin_id'],
            'service'=>$data['service'],
            'service_type'=>$data['service_type'],
        ]);
    }

    public function item()
    {
        $this->belongsTo('App\Models\Item');
    }
}
