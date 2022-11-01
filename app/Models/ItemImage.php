<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $fillable = [
        'item_id', 'source',
    ];

    static function createItemImage($source, $item_id)
    {
    	return ItemImage::create([
    		'source' => $source,
    		'item_id' => $item_id,
    	]);
    }

    static function deleteItemImage($id, $item_id)
    {
        $image = ItemImage::where('id', $id)->where('item_id',$item_id)->first();
        if ($image) {
            $image->delete();
        }
        return $image;
    }

    public function item()
    {
        return $this->belongsTo('App\Model\Item');
    }

}
