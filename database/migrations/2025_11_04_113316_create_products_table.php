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
        Schema::create('products', function (Blueprint $table) {
            $table->id('ProductID');
            $table->unsignedBigInteger('CategoryID')->nullable();
            $table->string('ProductName', 150);
            $table->text('Description')->nullable();
            $table->decimal('Price', 10, 2);
            $table->integer('StockQuantity')->default(0);
            $table->string('Image', 255)->nullable();
            $table->timestamps();

            $table->foreign('CategoryID')->references('CategoryID')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
