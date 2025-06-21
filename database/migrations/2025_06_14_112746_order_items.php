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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Auto increment primary key
            $table->string('order_id', 10); // Foreign key ke orders table
            $table->string('menu_id', 5); // Foreign key ke menus table
            $table->string('menu_name', 60); // Snapshot nama menu saat order
            $table->integer('quantity');
            $table->integer('price'); // Harga per item saat order
            $table->integer('discount_percent')->default(0); // Persentase diskon saat order
            $table->integer('subtotal'); // Subtotal sebelum diskon
            $table->integer('discount_amount')->default(0); // Jumlah diskon dalam rupiah
            $table->integer('subtotal_after_discount'); // Subtotal setelah diskon
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('menu_id')->references('menu_id')->on('menus')->onDelete('restrict');
            
            // Index
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};