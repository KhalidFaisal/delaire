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
    Schema::create('brandcats', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('brand_id');
        $table->unsignedBigInteger('main_Cat');
        $table->timestamps();

        $table->foreign('brand_id')
              ->references('id')
              ->on('probrands')
              ->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brandcats');
    }
};
