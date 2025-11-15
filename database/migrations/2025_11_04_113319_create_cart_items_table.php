<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('CartItemID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('ProductID');
            $table->integer('Quantity')->default(1);
            $table->timestamps();

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('ProductID')->references('ProductID')->on('products')->onDelete('cascade');

            $table->unique(['UserID', 'ProductID']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
