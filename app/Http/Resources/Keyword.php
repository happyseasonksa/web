<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Keyword extends JsonResource
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
            'user_id' => $this->user_id,
            'username' => $this->user?$this->user->name:null,
            'keyword' =>$this->keyword
        ];
    }
}
