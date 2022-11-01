<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
         'image_category_id', 'status','image','x_axis', 'y_axis', 'color', 'font',
    ];

    public function createCard($data)
    {
        return Card::create([
            'image_category_id' => $data['image_category_id'],
            'status' => ($data['status'] == 'true')?true:false,
            "image" => ($data['image'])?asset($data['image']):"",
            "x_axis"=>$data['x_axis']??null,
            "y_axis"=>$data['y_axis']??null,
            "color"=>$data['color']??null,
            "font"=>$data['font']??null,

        ]);
    }

    public function updateCard($data,$category)
    {
    	if (isset($data['image_category_id'])) {
            $category->image_category_id = $data['image_category_id'];
        }
        if (isset($data['status'])) {
            $category->status = ($data['status'] == 'true')?true:false;
        }
        if (isset($data['image'])) {
            $category->image = isset($data['image'])?asset($data['image']):'';
        }
        if (isset($data['x_axis'])) {
            $category->x_axis = $data['x_axis'];
        }
        if (isset($data['y_axis'])) {
            $category->y_axis = $data['y_axis'];
        }
        if (isset($data['color'])) {
            $category->color = $data['color'];
        }
        if (isset($data['font'])) {
            $category->font = $data['font'];
        }
        $category->save();
        return $category;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\ImageCategory','image_category_id');
    }
}
