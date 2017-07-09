<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppinglistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->string('unit', 30);
            $table->integer('subtotal_price')->default(0);
            $table->boolean('is_priority');
            $table->integer('product_id');
            $table->integer('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_list');
    }
}
