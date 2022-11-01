<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredient;
use App\Models\AdminNotification;
use App\Models\Admin;
use App\Models\RestaurantBranch;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'amount', 'total_amount', 'quantity','add_on','is_preOrder','removables','cooking_styles'
    ];

    public function createOrderitem($data)
    {   
        $orderItem = OrderItem::create([
            'order_id' => $data['order_id'],
            'product_id' => $data['product_id'],
            'amount' => $data['amount'],
            'total_amount' => $data['total_amount'],
            'quantity' => $data['quantity'],
            'is_preOrder' => isset($data['is_preOrder'])?(int)$data['is_preOrder']:0,
            'add_on' => isset($data['add_on'])?$data['add_on']:null,
            'removables' => isset($data['removables'])?$data['removables']:null,
            'cooking_styles' => isset($data['cooking_styles'])?$data['cooking_styles']:null,
        ]);
        $this->updateInventoryCount($orderItem);
        return $orderItem;
    }

    public function updateOrderitem($data, $orderItem)
    {   
        $orderItem->amount = $data['amount'];
        $orderItem->total_amount = $data['total_amount'];
        $orderItem->quantity = $data['quantity'];
        $orderItem->add_on = $data['add_on'];
        $orderItem->removables = $data['removables'];
        $orderItem->cooking_styles = $data['cooking_styles'];
        $this->updateInventoryCount($orderItem);
        $orderItem->save();
        return $orderItem;
    }

    public function updateInventoryCount($orderItem)
    {
        if ($orderItem && $orderItem instanceof OrderItem && $orderItem->product->Ingredients->count() > 0) {
            $PIngredients = $orderItem->product->Ingredients;
            foreach ($PIngredients as $key => $PIngredient) {
                $ingredient = $PIngredient->ingredient;
                if ($ingredient && $ingredient instanceof Ingredient) {
                    $Pquantity = $PIngredient->quantity??0;
                    // check update item quentity
                    $check = ($orderItem->quantity != $orderItem->getOriginal('quantity'))?true:false;
                    // check update item quentity end
                    $Pquantity = $orderItem->quantity*$PIngredient->quantity;
                    if ($check) {
                        $checkCount = $orderItem->quantity - $orderItem->getOriginal('quantity');
                        $isNegative = $checkCount < 0;
                        if ($isNegative) {
                            $this->notifyBranchAdmin($ingredient);
                            $Pquantity = abs($checkCount)*$PIngredient->quantity;
                            $quantity = (int)$ingredient->quantity + (int)$Pquantity; 
                        }else{
                            $Pquantity = $checkCount*$PIngredient->quantity;
                            $quantity = max((int)$ingredient->quantity - (int)$Pquantity,0); 
                        }
                    }else{
                        $quantity = max((int)$ingredient->quantity - (int)$Pquantity,0);    
                    }
                    $ingredient->update(['quantity'=>$quantity]);
                }
            }
        }
        return $orderItem;
    }

    // notify admin when ingredient quantity is low 
    public function notifyBranchAdmin($ingredient)
    {   
        $branch = $ingredient->branch;
        if ($branch && $branch instanceof RestaurantBranch) {
            $title = "Ingredient shortage at ".$branch->name;
            $body = $ingredient->name." is low in quantity at ".$branch->name;
            $adminIds = $branch->associateAdmins()->get()->pluck('admin_id')->toArray();
            if (count($adminIds) > 0) {
                $admins = Admin::find($adminIds);
                if (count($admins)) {
                    foreach ($admins as $key => $admin) {
                        AdminNotification::createAdminNotification($admin,$title,$body,'fas fa-exclamation-circle');
                    }
                }
            }
        }
        return $ingredient;
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
