<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Customer as CustomerResource;

class Review extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'user_id' => $this->user_id,
            'user_details'=>($this->user)?new User($this->user):[],
            'star' => $this->star,
            'comment' => $this->comment,
            'status' => $this->status,
            'created_at' => (string) date('M d ,Y',strtotime($this->created_at)),
        ];
    }
}
