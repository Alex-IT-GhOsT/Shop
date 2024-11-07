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
        Schema::create('laptops', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('current_price')->nullable();
            $table->string('old_price')->nullable();
            $table->string('href')->nullable();
            $table->string('image_src')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('reviews_link')->nullable();
            $table->string('discount')->nullable();
            $table->string('superprice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptops');
    }
};
