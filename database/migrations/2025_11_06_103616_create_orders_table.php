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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderID');
            $table->unsignedBigInteger('UserID'); // cột khóa ngoại
            $table->dateTime('OrderDate')->useCurrent();
            $table->decimal('TotalAmount', 10, 2);
            $table->string('Status', 50)->default('pending');
            $table->timestamps();

            // Liên kết foreign key đúng tên cột
            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
