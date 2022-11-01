<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
         'name', 'ar_name','country_id'
    ];

    public function createCity($data)
    {
        return City::create([
            'name' => $data['name'],
            'country_id' => $data['country_id'],
            'ar_name' => isset($data['ar_name'])?$data['ar_name']:null,

        ]);
    }

    public function updateCity($data,$city)
    {
    	if (isset($data['name'])) {
            $city->name = $data['name'];
        }
        if (isset($data['country_id'])) {
            $city->country_id = $data['country_id'];
        }
        if (isset($data['ar_name'])) {
            $city->ar_name = $data['ar_name'];
        }
        $city->save();
        return $city;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}
