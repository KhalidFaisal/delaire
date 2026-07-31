<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_data', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email_verified_at');
            $table->text('address')->nullable()->after('password');
            $table->text('shipping_address')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('user_data', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'address', 'shipping_address']);
        });
    }
};
