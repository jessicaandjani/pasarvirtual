<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Response;

class OrderStatusController extends Controller
{
    function getOrderStatus(Request $id) {
    	$status = Order::find($id);

    	return Response::json(array(
    		'error'=>false,
    		'status'=>$status),
    		200
    	);
    }
}
