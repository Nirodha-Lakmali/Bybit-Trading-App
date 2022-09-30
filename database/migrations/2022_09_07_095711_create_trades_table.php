<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('symbol'); //symbol id
            $table->string('side'); //buy or sell
            $table->float('qty');
            $table->string('order_type');
            $table->double('price');
            $table->float('repurchase');
            $table->integer('leverage');
            $table->string('order_status');//New,Filled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trades');
    }
};
