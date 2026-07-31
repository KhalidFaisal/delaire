<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        // Insert default roles with default permissions seeded
        DB::table('roles')->insert([
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'permissions' => json_encode(['dashboard', 'inventory', 'current_stock', 'damage_stock', 'pro_category', 'pro_sub_category', 'pro_brand', 'products', 'reviews', 'create_order', 'all_orders', 'return_requests', 'blog_category', 'write_blog', 'all_blogs', 'portfolio', 'content', 'feature_category', 'content_setting', 'testimonial', 'offers', 'charges', 'promo_codes', 'app_settings', 'email_account', 'login_settings', 'manage_admin', 'logs', 'customers']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'permissions' => json_encode(['dashboard', 'inventory', 'current_stock', 'damage_stock', 'pro_category', 'pro_sub_category', 'pro_brand', 'products', 'reviews', 'create_order', 'all_orders', 'return_requests', 'blog_category', 'write_blog', 'all_blogs', 'portfolio', 'content', 'feature_category', 'content_setting', 'testimonial', 'offers', 'charges', 'promo_codes', 'app_settings', 'email_account', 'login_settings', 'manage_admin', 'logs']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'permissions' => json_encode(['dashboard', 'inventory', 'current_stock', 'damage_stock', 'pro_category', 'pro_sub_category', 'pro_brand', 'products', 'reviews', 'create_order', 'all_orders', 'return_requests']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'permissions' => json_encode(['dashboard', 'blog_category', 'write_blog', 'all_blogs', 'portfolio', 'content', 'feature_category', 'content_setting', 'testimonial']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
