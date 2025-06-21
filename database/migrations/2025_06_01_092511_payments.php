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
        Schema::create('payments', function (Blueprint $table) {
            $table->string('method_id', 5)->primary(); // Primary key
            $table->string('method_name', 60); // Example: QRIS, Bank Transfer, COD
            $table->string('description', 100)->nullable(); // Short description
            $table->string('static_proof', 100)->nullable(); // Static image file name
            $table->string('destination_account', 20)->nullable(); // Bank account number if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
