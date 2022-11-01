<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    protected $fillable=[
        "user_id", "card_id", "message", "item_name", "item_id", "address", "lat", "lng", "invitation_date",'image', "signature","status"
    ];

    public function createInvitation($data)
    {
        return Invitation::create([
            'user_id' => $data['user_id'],
            'card_id' => $data['card_id'],
            'message' => $data['message'],
            'item_name' => $data['item_name']??null,
            'item_id' => $data['item_id']??null,
            'address' => $data['address'],
            'lat' => $data['lat']??null,
            'lng' => $data['lng']??null,
            'invitation_date' => $data['invitation_date'],
            'signature' => $data['signature']??null,
            'image' => $data['image']??null,
            'status'=>isset($data['status'])?$data['status']:0,
        ]);
    }

    public function UpdateInvitation($data, Item $item)
    {
        if (isset($data['card_id'])){
            $item->card_id=$data['card_id'];
        }
        if (isset($data['message'])){
            $item->message=$data['message'];
        }
        if (isset($data['item_name'])){
            $item->item_name=$data['item_name'];
        }
        if (isset($data['item_id'])){
            $item->item_id=$data['item_id'];
        }
        if (isset($data['address'])){
            $item->address=$data['address'];
        }
        if (isset($data['lat'])){
            $item->lat=$data['lat'];
        }
        if (isset($data['lng'])){
            $item->lng=$data['lng'];
        }
        if (isset($data['invitation_date'])){
            $item->invitation_date=$data['invitation_date'];
        }
        if (isset($data['signature'])){
            $item->signature=$data['signature'];
        }
        if (isset($data['image'])){
            $item->image=$data['image'];
        }
        if (isset($data['status'])){
            $item->status=$data['status'];
        }
        if (isset($data['phone'])){
            $item->phone=$data['phone'];
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

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function card()
    {
        return $this->belongsTo('App\Models\Card');
    }

    public function item()
    {
        return $this->hasOne('App\Models\Item','item_id');
    }

    public function invitees()
    {
        return $this->hasMany('App\Models\InvitationUsers');
    }

}
