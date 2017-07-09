<?php

namespace App\Http\Controllers;

use App\ShoppingList;
use Illuminate\Http\Request;
use Response;

class ShoppingListController extends Controller
{
     public function addShoppingList(Request $request, $id) {
        $order_line = json_decode($request->order_line, true);
        foreach ($order_line as $item) {
            $shoppingList = new ShoppingList();
            $shoppingList->order_id = $id;
            $shoppingList->product_id = $item['productId'];
            $shoppingList->quantity = $item['quantity'];
            $shoppingList->unit = $item['unit'];
            $shoppingList->is_priority = $item['isPriority'];
            $shoppingList->save();
        }
        return "Order Anda berhasil dimasukan";
    }

    public function getAllOrderLine() {
        //retrieve all categories from datbase
        $orderline = ShoppingList::all();
        
        return Response::json(array(
            'orderline'=>$orderline->toArray()),
            200
        );
    }
}
