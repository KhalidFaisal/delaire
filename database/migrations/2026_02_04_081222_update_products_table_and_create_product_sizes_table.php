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
        Schema::table('products', function (Blueprint $table) {
            $table->string('pro_img2')->nullable();
            $table->string('pro_img3')->nullable();
            $table->text('pro_short_desc')->nullable();
            $table->string('pro_size_chart')->nullable(); // Replaces/adds to usage of pro_datasheet if needed
        });

        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('size');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sizes');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['pro_img2', 'pro_img3', 'pro_short_desc', 'pro_size_chart']);
        });
    }
};
