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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('OrderItemID');
            $table->unsignedBigInteger('OrderID');
            $table->unsignedBigInteger('ProductID');
            $table->integer('Quantity');
            $table->decimal('UnitPrice', 10, 2); // ✅ thêm dòng này
            $table->decimal('TotalPrice', 10, 2); // ✅ và dòng này
            $table->timestamps();

            $table->foreign('OrderID')->references('OrderID')->on('orders')->onDelete('cascade');
            $table->foreign('ProductID')->references('ProductID')->on('products')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
