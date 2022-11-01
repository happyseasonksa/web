<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable=[
        'name', 'description', 'lat', 'lng','admin_id','city_id','category_id','subcategory_id', 'address','website', 'offers', 'openTimes', 'services', 'staff', 'facilities', 'phone', 'facebook', 'twitter', 'instagram', 'whatsapp','status'
    ];

    public function createItem($data)
    {

        return Item::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'admin_id' => $data['admin_id'],
            'city_id' => $data['city_id'],
            'category_id' => $data['category_id'],
            'subcategory_id' => $data['subcategory_id']??null,
            'address' => $data['address']??null,
            'openTimes' => $data['openTimes']??null,
            'website' => $data['website']??null,
            'phone' => $data['phone'],
            'facebook' => $data['facebook']??null,
            'twitter' => $data['twitter']??null,
            'instagram' => $data['instagram']??null,
            'whatsapp' => $data['whatsapp']??null,
            'status'=>(isset($data['status']) && $data['status'] == 1)?1:0,
        ]);
    }

    public function UpdateItem($data, Item $item)
    {
        if (isset($data['name'])){
            $item->name=$data['name'];
        }
        if (isset($data['description'])){
            $item->description=$data['description'];
        }
        if (isset($data['lat'])){
            $item->lat=$data['lat'];
        }
        if (isset($data['lng'])){
            $item->lng=$data['lng'];
        }
        if (isset($data['city_id'])){
            $item->city_id=$data['city_id'];
        }
        if (isset($data['category_id'])){
            $item->category_id=$data['category_id'];
        }
        if (isset($data['subcategory_id'])){
            $item->subcategory_id=$data['subcategory_id'];
        }
        if (isset($data['address'])){
            $item->address=$data['address'];
        }

        if (isset($data['openTimes'])){
            $item->openTimes=$data['openTimes'];
        }
        if (isset($data['website'])){
            $item->website=$data['website'];
        }
        if (isset($data['phone'])){
            $item->phone=$data['phone'];
        }
        if (isset($data['facebook'])){
            $item->facebook=$data['facebook'];
        }
        if (isset($data['twitter'])){
            $item->twitter=$data['twitter'];
        }
        if (isset($data['instagram'])){
            $item->instagram=$data['instagram'];
        }
        if (isset($data['whatsapp'])){
            $item->whatsapp=$data['whatsapp'];
        }
        if (isset($data['status'])) {
            $item->status = ($data['status'])?1:0;
        }

        $item->save();
        return $item;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ItemImage','item_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review','item_id');
    }

    public function services()
    {
        return $this->hasMany('App\Models\ItemServices')->where('service_type','services')->get();
    }
    public function staff()
    {
        return $this->hasMany('App\Models\ItemServices')->where('service_type','staff')->get();
    }
    public function facilities()
    {
        return $this->hasMany('App\Models\ItemServices')->where('service_type','facilities')->get();
    }
     public function offers()
    {
        return $this->hasMany('App\Models\ItemServices')->where('service_type','offers')->get();
    }

}
