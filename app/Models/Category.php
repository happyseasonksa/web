<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
         'name', 'ar_name', 'status','icon'
    ];

    public function createCategory($data)
    {
        return Category::create([
            'name' => $data['name'],
            'ar_name' => isset($data['ar_name'])?$data['ar_name']:null,
            'status' => ($data['status'] == 'true')?true:false,
            "icon" => ($data['icon'])?asset($data['icon']):"",

        ]);
    }

    public function updateCategory($data,$category)
    {
    	if (isset($data['name'])) {
            $category->name = $data['name'];
        }
        if (isset($data['ar_name'])) {
            $category->ar_name = $data['ar_name'];
        }
        if (isset($data['status'])) {
            $category->status = ($data['status'] == 'true')?true:false;
        }
        if (isset($data['icon'])) {
            $category->icon = isset($data['icon'])?asset($data['icon']):'';
        }
        $category->save();
        return $category;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function subcategories()
    {
        return $this->hasMany('App\Models\SubCategory');
    }
    public function ads()
    {
        return $this->hasMany('App\Models\Ads');
    }
}
