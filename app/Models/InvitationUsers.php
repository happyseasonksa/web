<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationUsers extends Model
{
    use HasFactory;
    protected $fillable = [
        'invitee_id', 'inviter_id', 'invitation_id','phone'
    ];

    public function createItem($data)
    {
        return InvitationUsers::create([
            'invitee_id'=>$data['invitee_id']??null,
            'inviter_id'=>$data['inviter_id'],
            'invitation_id'=>$data['invitation_id'],
            'phone' =>$data['phone'],
        ]);
    }
    public function invitee()
    {
        return $this->belongsTo('App\Models\User','invitee_id');
    }

    public function inviter()
    {
        return $this->belongsTo('App\Models\User','inviter_id');
    }

    public function invitation()
    {
        return $this->belongsTo('App\Models\Invitation');
    }
}
