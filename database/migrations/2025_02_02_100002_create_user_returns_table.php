<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_data')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('user_orders')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('requested'); // requested, approved, rejected, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_returns');
    }
};
