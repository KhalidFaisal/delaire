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
        Schema::table('user_orders', function (Blueprint $table) {
            $table->string('shipping_name')->after('user_id');
            $table->string('shipping_email')->after('shipping_name');
            $table->string('shipping_phone')->after('shipping_email');
            $table->text('shipping_address')->after('shipping_phone');
            $table->string('shipping_city')->after('shipping_address');
            $table->string('shipping_zip')->after('shipping_city');
            $table->string('payment_method')->default('COD')->after('total');
        });
    }

    public function down(): void
    {
        Schema::table('user_orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name', 'shipping_email', 'shipping_phone', 
                'shipping_address', 'shipping_city', 'shipping_zip', 
                'payment_method'
            ]);
        });
    }
};
