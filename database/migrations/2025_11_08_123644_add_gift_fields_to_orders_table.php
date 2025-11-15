<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('DeliveryDateTime')->nullable()->after('OrderDate');
            $table->string('RecipientAddress', 255)->nullable()->after('DeliveryDateTime');
            $table->text('MessageToRecipient')->nullable()->after('RecipientAddress');
            $table->string('PaymentMethod', 50)->default('cash')->after('MessageToRecipient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['DeliveryDateTime', 'RecipientAddress', 'MessageToRecipient', 'PaymentMethod']);
        });
    }
};
