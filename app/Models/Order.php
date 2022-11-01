<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DriverTask;
use App\Models\TaskDriver;
use App\Models\User;
use App\Models\Cart;
use Twilio\Rest\Client;

class Order extends Model
{
    protected $fillable = [
        'customer_id' ,'driver_id', 'ads_id', 'large_num','medium_num','small_num', 'docs_num', 'transaction_id','total_amount','note', 'processing_at', 'accepted_at', 'on_the_way_at','picked_up_at','cancelled_at','delivered_at','completed_at','status','payment_mode','payment_status','entity_type'
    ];

    /*
		STATUS
		0=Pending, 1=Processing, 2=Accepted, 3=completed ,4=Cancelled
    */
    const STATUS_PENDING=0;
    const STATUS_PROCESSING=1;
    const STATUS_ACCEPTED=2;
    const STATUS_COMPLETED=3;
    const STATUS_CANCELLED=4;
    const STATUS_ON_WAY=5;
    const STATUS_PICKED_UP=6;
    const STATUS_DELIVERED=7;
	/*
		PAYMENT_MODE
		0=Cash, 1=Card, 2=Online
    */
    /*
		PAYMENT_STATUS
		0=Pending, 1=Processing, 2=Completed
    */

    public function createOrder($data)
    {
        $order = Order::create([
            'customer_id' => $data['customer_id'],
            'driver_id' => $data['driver_id'],
            'ads_id' => $data['ads_id'],
            'large_num' => $data['large_num'],
            'medium_num' => $data['medium_num'],
            'small_num' => $data['small_num'],
            'docs_num' => $data['docs_num'],
            'entity_type' => isset($data['entity_type'])?$data['entity_type']:'user',
            'transaction_id' => isset($data['transaction_id'])?$data['transaction_id']:null,
            'total_amount' => $data['total_amount'],
            'note' => isset($data['note'])?$data['note']:null,
            'status'=>isset($data['status'])?$data['status']:0,
        ]);
        return $order;
    }
    public function updateOrder($data,Order $order)
    {

        if (isset($data['large_num'])){
            $order->large_num = $data['large_num'];
        }
        if (isset($data['medium_num'])){
            $order->medium_num = $data['medium_num'];
        }
        if (isset($data['small_num'])){
            $order->small_num = $data['small_num'];
        }
        if (isset($data['docs_num'])){
            $order->docs_num = $data['docs_num'];
        }
        if (isset($data['total_amount'])){
            $order->total_amount = $data['total_amount'];
        }
        if (isset($data['note'])){
            $order->note = $data['note'];
        }
        if (isset($data['transaction_id'])){
            $order->transaction_id = $data['transaction_id'];
        }
        $order->save();
        return $order;
    }

    public function changeOrderStatus($order, $status)
    {
        $userObj = new User;
        $status = (int) $status;
        $customerUser = null;
        $customerTitle = "";
        $customerMsg = "";
        $driverUser = null;
        $driverTitle = "";
        $driverMsg = "";
        $productNames = "";
        if ($order) {
            $order->status = $status;
            $order->save();
        }
        switch ($status) {
            case 1:
                if ($order && $order->customer && $order->customer->user) {

                    \App::setLocale($order->customer->user->language);
                    $customerUser = $order->customer->user;
                    $customerTitle = __('api.notification.customer_order_processing_title');
                    $customerMsg = __('api.notification.customer_order_processing_noti', ['orderId'=>$order->id]);
                    $order->status = Order::STATUS_PROCESSING;
                }
            break;
            case 2:
                if ($order && $order->customer && $order->customer->user) {
                    \App::setLocale($order->customer->user->language);
                    $customerUser = $order->customer->user;
                    $customerTitle = __('api.notification.customer_order_pay_title');
                    $customerMsg = __('api.notification.customer_order_completed_noti', ['orderId'=>$order->id,'productNames'=>$productNames]);
                    $order->status = Order::STATUS_ACCEPTED;
                }
            break;
            case 3:
                if ($order && $order->customer && $order->customer->user) {
                    \App::setLocale($order->customer->user->language);
                    $customerUser = $order->customer->user;
                    $customerTitle = __('api.notification.customer_order_completed_title');
                    $customerMsg = __('api.notification.customer_order_completed_noti', ['orderId'=>$order->id]);
                    $order->status = Order::STATUS_COMPLETED;
                }
                break;
            case 4:
                if ($order && $order->customer && $order->customer->user) {
                    \App::setLocale($order->customer->user->language);
                    $productNames = $order->getOrderTotalName($order,$order->customer->user->language);
                    $customerUser = $order->customer->user;
                    $customerTitle = __('api.notification.customer_order_cancelled_title');
                    $customerMsg = __('api.notification.customer_order_cancelled_noti', ['orderId'=>$order->id]);
                    $order->status = Order::STATUS_CANCELLED;
                }
            break;
            case 5:
                if ($order && $order->customer && $order->customer->user) {
                    \App::setLocale($order->customer->user->language);
                    $customerUser = $order->customer->user;
                    $customerTitle = __('api.notification.customer_order_on_the_way_title');
                    $customerMsg = __('api.notification.customer_order_on_the_way_noti', ['orderId'=>$order->id,'productNames'=>$productNames]);
                }
            break;
            case 6:
                if ($order && $order->customer && $order->customer->user) {
                    \App::setLocale($order->customer->user->language);
                    $productNames = $order->getOrderTotalName($order,$order->customer->user->language);
                    $customerUser = $order->customer->user;
                    $customerTitle = __('api.notification.customer_order_picked_up_title');
                    $customerMsg = __('api.notification.customer_order_picked_up_noti', ['orderId'=>$order->id,'productNames'=>$productNames]);
                }
            break;
            case 7:
                if ($order && $order->customer && $order->customer->user) {
                    \App::setLocale($order->customer->user->language);
                    $customerUser = $order->customer->user;
                    $customerTitle = __('api.notification.driver_order_delivered_title');
                    $customerMsg = __('api.notification.driver_order_delivered_noti', ['orderId'=>$order->id,'productNames'=>$productNames]);
                }
            break;
        }

        if ($customerUser) {
            $userObj->sendNotification($customerTitle,$customerMsg,$customerUser);
            // $userObj->orderEmail($order,$productNames,$customerUser);
        }
        if ($driverUser) {
            $driverObj=new Driver;
            $driverObj->sendNotification($driverTitle,$driverMsg,$driverUser);
        }
        $this->updateOrderDateWithStatus($order);
        \App::setLocale("en");
        return $order;
    }

    public function updateOrderDateWithStatus($order)
    {
        try {
            if (isset($order->status)) {
                $date = date('Y-m-d H:i:s');
                switch ((int)$order->status) {
                    case 1:
                        if (is_null($order->processing_at)) {
                            $order->processing_at = $date;
                        }
                        break;
                    case 2:
                        if (is_null($order->accepted_at)) {
                            $order->accepted_at = $date;
                        }
                        break;
                    case 3:
                        if (is_null($order->completed_at)) {
                            $order->completed_at = $date;
                        }
                        break;
                    case 4:
                        if (is_null($order->cancelled_at)) {
                            $order->cancelled_at = $date;
                        }
                        break;
                    case 5:
                        if (is_null($order->on_the_way_at)) {
                            $order->on_the_way_at = $date;
                        }
                        break;
                    case 6:
                        if (is_null($order->picked_up_at)) {
                            $order->picked_up_at = $date;
                        }
                        break;
                    case 7:
                        if (is_null($order->delivered_at)) {
                            $order->delivered_at = $date;
                        }
                        break;
                }
                $order->save();
            }
        } catch (\Exception $e) {

        }
        return $order;
    }

    public function getOrderTotalName($order,$lang="en")
    {
        $name = "";
        $worth = ($lang=="en")?' worth of SAR':' بقيمة ريال سعودي';
        if ($order && $order instanceof Order) {
            $name = $worth." ".$order->total_amount;
        }
        return $name;
    }

    public function createDriverTask($order)
    {
        $restaurant = $order->restaurant;
        $userObj = new User;
        if ($restaurant) {
            $productNames = $order->getOrderTotalName($order);
            // $drivers = $restaurant->drivers()->where('service_status', 1)->get();
            $drivers = getDriverNearRest($restaurant,$order->branch);
            $firstDriver = (count($drivers) > 0)?$drivers->first():null;
            if ($firstDriver) {
                // send notification to driver for task assigned
                $driverTask = DriverTask::createDriverTask($order->id,$firstDriver->id??null);
                $driverTitle = __('api.notification.driver_order_assigned_title');
                $driverMsg = __('api.notification.driver_order_assigned_noti', ['orderId'=>$order->id,'productNames'=>$productNames]);
                $userObj->sendNotification($driverTitle,$driverMsg,$firstDriver->user);
                // send notification to driver for task assigned ENDS
                if ($driverTask && count($drivers) > 0) {
                    foreach ($drivers as $key => $driver) {
                        if ($key !== 0) {
                            TaskDriver::createTaskDriver($driverTask->id,$driver->id);
                        }
                    }
                    return $driverTask;
                }
            }else{
                sendNoDriverNotification($order);
            }
        }
        return false;
    }

    public function calculateTotalAmount($ads,$req)
    {
        $total=0;
        if (isset($req->large_num)){
            $total+=(int)$req->large_num*(int)$ads->large_price;

        }if (isset($req->medium_num)){
            $total+=(int)$req->medium_num*(int)$ads->medium_price;

        }if (isset($req->small_num)){
            $total+=(int)$req->small_num*(int)$ads->small_price;

        }if (isset($req->docs_num)){
            $total+=(int)$req->docs_num*(int)$ads->docs_price;
        }
        return $total;
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\Driver');
    }

    public function ads()
    {
        return $this->belongsTo('App\Models\Ads');
    }
    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }
}
