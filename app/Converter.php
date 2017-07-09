<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Converter extends Model
{
    public $table = "converter";
    public $timestamps = false;

    public function unit() {
        return $this->belongsTo('App\Unit');
    }
}
