<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'title_en', 'image', 'description', 'admin_id', 'start_at', 'end_at', 'status','target','link','item_id'
    ];

    public function createAds($data)
    {
        return Ads::create([
            'title' => $data['title'],
            'title_en' => $data['title_en']??null,
            'admin_id' => $data['admin_id'],
            'start_at' => $data['start_at'],
            'target' => $data['target'],
            'link' => $data['link']??null,
            'item_id' => $data['item_id']??null,
            'end_at' => $data['end_at'],
            'description' => $data['description']??null,
            'image' => $data['image'],
            'status' => $data['status'],
        ]);
    }

    public function updateAds($data,$ads)
    {

        if (isset($data['title'])) {
            $ads->title = $data['title'];
        }
        if (isset($data['title_en'])) {
            $ads->title_en = $data['title_en'];
        }
        if (isset($data['image'])) {
            $ads->image = $data['image'];
        }
        if (isset($data['description'])) {
            $ads->description = $data['description'];
        }
        if (isset($data['target'])) {
            $ads->target = $data['target'];
        }
        if (isset($data['link'])) {
            $ads->link = $data['link'];
        }
        if (isset($data['item_id'])) {
            $ads->item_id = $data['item_id'];
        }
        if (isset($data['start_at'])) {
            $ads->start_at = $data['start_at'];
        }
        if (isset($data['end_at'])) {
            $ads->end_at = $data['end_at'];
        }
        if (isset($data['status'])) {
            $ads->status = ($data['status'] == 'true')?true:false;
        }
        $ads->save();
        return $ads;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
}
