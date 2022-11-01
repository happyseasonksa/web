<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'item_id','user_id','star','comment','status',
    ];

    static function createReview($data)
    {
        return Review::create([
            'item_id' => $data['item_id'],
            'user_id' => $data['user_id'],
            'comment' => isset($data['comment'])?$data['comment']:'',
            'star' => $data['star'],
            'status' => ($data['status'] == 'true')?true:false,
        ]);
    }

    public function updateReview($data,$review)
    {
        if (isset($data['star'])) {
            $review->star = $data['star'];
        }
        $review->comment = isset($data['comment'])?$data['comment']:'';

        if (isset($data['status'])) {
            $review->status = ($data['status'] == 'true')?true:false;
        }
        print_r($review);
        die();
        $review->save();
        return $review;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function item()
    {
    	return $this->belongsTo('App\Models\Item');
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }
}
