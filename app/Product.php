<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = "product";

    public function unit() {
    	return $this->belongsTo('App\Unit');
    }
}

