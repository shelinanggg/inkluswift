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
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); // auto increment primary key
            $table->string('user_id', 5); // foreign key ke users table
            $table->string('menu_id', 5); // foreign key ke menus table
            $table->integer('quantity');
            $table->integer('price'); // price saat item ditambahkan ke cart (untuk handle perubahan harga)
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('menu_id')->references('menu_id')->on('menus')->onDelete('cascade');
            
            // Unique constraint untuk mencegah duplicate item dari user yang sama
            $table->unique(['user_id', 'menu_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};