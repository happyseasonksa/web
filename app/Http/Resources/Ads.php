<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Ads extends JsonResource
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
            'title' => $this->title,
            'image' => ($this->image)?asset($this->image):null,
            'description' => $this->description??null,
            'target' =>$this->target,
            'link' =>$this->link??null,
            'item_id' =>$this->item_id??null,
            'star_at' => $this->start_at,
            'end_at' => $this->end_at,
        ];
    }
}
