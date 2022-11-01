<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageCategory extends JsonResource
{
    public function __construct($resource)
    {
        $this->local = getLocalAttribute();
        parent::__construct($resource);
    }
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
            'name' => getResInputAsLocal('name',$this, $this->local),
            'status' => $this->status,
        ];
    }
}
