<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    use HasFactory;
    protected $fillable=['name','value'];
    const MAX_DISTANCE='max search distance';
    const MESSAGE_SETTING='message_setting';
    public function createSetting($data)
    {
        return Setting::create([
            'name' => $data['name'],
            'value' => $data['value'],
        ]);
    }

    public function updateSetting($data,$setting)
    {
        if (isset($data['name'])) {
            $setting->title = $data['name'];
        }
        if (isset($data['value'])) {
            $setting->value = $data['value'];
        }
        $setting->save();
        return $setting;
    }
}
