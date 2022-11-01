<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'name','ar_name','status'
    ];

    public function createImageCategory($data)
    {	
        return ImageCategory::create([
            'name' => $data['name'],
            'ar_name' => $data['ar_name']??"",
            'status' => ($data['status'] == 'true')?1:0,
        ]);
    }

    public function updateImageCategory($data,$category)
    {	
    	if (isset($data['name'])) {
            $category->name = $data['name'];
        }
        if (isset($data['ar_name'])) {
            $category->ar_name = $data['ar_name'];
        }
        if (isset($data['status'])) {
            $category->status = ($data['status'] == 'true')?1:0;
        }
        $category->save();
        return $category;
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function banners()
    {
        return $this->hasMany('App\Models\RestaurantBanner');
    }
}
