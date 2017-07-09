<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $table = "category";
    
    public function products() {
    	return $this->hasMany('App\Product');
    }
}