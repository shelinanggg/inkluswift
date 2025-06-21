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
        Schema::create('menus', function (Blueprint $table) {
            $table->string('menu_id', 5)->primary(); // MNU0001, MNU0002, etc.
            $table->string('menu_name', 60);
            $table->integer('price')->default(0); // price in cents to avoid floating point issues
            $table->integer('discount')->default(0); // percentage
            $table->enum('category', ['foods', 'drinks', 'snacks']);
            $table->text('description')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('storage')->nullable();
            $table->string('image')->nullable(); // path to uploaded image
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};