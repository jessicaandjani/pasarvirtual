<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    public $table = "order_lines";

    public function order() {
    	return $this->belongsTo('App\Order', 'order_id');
    }
}
