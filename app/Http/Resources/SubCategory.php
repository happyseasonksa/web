<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category;

class SubCategory extends JsonResource
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
            'category_detail' => new Category($this->category)
        ];
    }
}
