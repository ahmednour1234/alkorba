<?php

// database/migrations/xxxx_xx_xx_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('address_id');
            $table->string('phone');
            $table->text('note')->nullable();
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');

        });
    }


    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
