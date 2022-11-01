<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
         'category_id', 'name', 'ar_name', 'status','image'
    ];

    public function createSubCategory($data)
    {
        return SubCategory::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'ar_name' => isset($data['ar_name'])?$data['ar_name']:null,
            //"image" => ($data['image'])?asset($data['image']):"",
            'status' => ($data['status'] == 'true')?true:false,
        ]);
    }

    public function updateSubCategory($data,$subcategory)
    {
    	if (isset($data['category_id'])) {
            $subcategory->category_id = $data['category_id'];
        }
        if (isset($data['name'])) {
            $subcategory->name = $data['name'];
        }
        if (isset($data['ar_name'])) {
            $subcategory->ar_name = $data['ar_name'];
        }
//        if (isset($data['image'])) {
//            $subcategory->image = isset($data['image'])?asset($data['image']):'';
//        }
        if (isset($data['status'])) {
            $subcategory->status = ($data['status'] == 'true')?true:false;
        }

        $subcategory->save();
        return $subcategory;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function category()
    {
    	return $this->belongsTo('App\Models\Category');
    }

    public function items()
    {
    	return $this->hasMany('App\Models\Item');
    }
}
