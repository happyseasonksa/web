<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAccess extends Model
{
    protected $fillable = [
        'admin_id', 'entity_name', 'add','update','delete','view'
    ];

    static function createAccess($admin_id,$entity,$view=0,$add=0,$update=0,$delete=0)
    {
    	$create = New AdminAccess;
    	$create->admin_id = $admin_id;
    	$create->entity_name = $entity;
    	$create->add = $add;
    	$create->update = $update;
    	$create->delete = $delete;
    	$create->view = $view;
    	$create->save();
    	return $create;
    }
}
