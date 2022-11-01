<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Page extends JsonResource
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
            'url' => route('cms.page',['name'=>$this->name]),
            'name' => $this->name,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
