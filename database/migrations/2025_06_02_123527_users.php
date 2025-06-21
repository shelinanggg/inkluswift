<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id', 5)->primary(); // custom ID (varchar 5)
            $table->string('name', 60);
            $table->string('email', 100)->unique();
            $table->string('password', 255); // hashed password
            $table->string('phone', 13)->nullable();
            $table->enum('role', ['admin', 'staff', 'customer']);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('address', 100)->nullable();
            $table->string('profile_picture', 255)->nullable();
            $table->date('join_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
