<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Texts extends Model
{
    use HasFactory;

    protected $fillable = [
        'stext', 'admin_id'
    ];

    public function createTexts($data)
    {
        return Texts::create([
            'stext'=>$data['stext'],
            'admin_id'=>$data['admin_id']??auth()->id(),
        ]);

    }

    public function updateTexts($data,Texts $text)
    {
        if (isset($data['stext'])){
            $text->stext=$data['stext'];
        }
        if (isset($data['admin_id'])){
            $text->stext=$data['admin_id'];
        }
        $text->save();
        return $text;
    }
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
}
