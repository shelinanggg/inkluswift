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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('order_id', 10)->primary(); // Custom order ID (e.g., ORD001)
            $table->string('user_id', 5); // Foreign key ke users table
            $table->string('method_id', 5); // Foreign key ke payments table
            $table->integer('total_amount'); // Total harga sebelum diskon
            $table->integer('total_discount')->default(0); // Total diskon
            $table->decimal('service_charge', 10, 2)->default(10000); // Biaya layanan
            $table->integer('final_amount'); // Total harga setelah diskon + service charge
            $table->string('customer_name'); // Nama customer
            $table->string('customer_phone'); // Nomor HP customer
            $table->text('customer_address'); // Alamat customer
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable(); // Catatan dari customer
            $table->string('proof_image', 100)->nullable(); // File bukti pembayaran
            $table->timestamp('confirmed_at')->nullable(); // Waktu konfirmasi
            $table->timestamp('completed_at')->nullable(); // Waktu selesai
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('method_id')->references('method_id')->on('payments')->onDelete('restrict');
            
            // Index untuk query yang sering digunakan
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};