<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Response;
use DateTime;

class OrderController extends Controller {

    public function addOrder(Request $request) {
    	$order = new Order();
        $order->order_at = new DateTime();
        //$order->order_at = date("Y-m-d H:i:s");
        var_dump($request->total_product);
        $order->total_product = $request->total_product;        
    	$order->buyer_id = $request->buyer_id;
        $order->garendong_id = 0;
        $order->orderstatus_id = 1;
        $order->order_type = "mobile";
        // $order->save();
        //get order id after insert the order to database
        $order_id = $order->id;
        return (string)$order_id;
    }


    public function getAllOrder() {
        //retrieve all categories from datbase
        $orders = Order::all();
        
        return Response::json(array(
            'orders'=>$orders->toArray()),
            200
        );
    }
}
