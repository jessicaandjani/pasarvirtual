<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    public $table = "shopping_list";
    public $timestamps = false;

    public function product() {
    	return $this->hasOne('App\Product');
    }

    public function order() {
    	return $this->hasOne('App\Order');
    }
}
