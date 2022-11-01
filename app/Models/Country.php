<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
         'name', 'ar_name'
    ];

    public function createCountry($data)
    {
        return Country::create([
            'name' => $data['name'],
            'ar_name' => isset($data['ar_name'])?$data['ar_name']:null,

        ]);
    }

    public function updateCountry($data,$country)
    {
    	if (isset($data['name'])) {
            $country->name = $data['name'];
        }
        if (isset($data['ar_name'])) {
            $country->ar_name = $data['ar_name'];
        }
        $country->save();
        return $country;
    }
}
