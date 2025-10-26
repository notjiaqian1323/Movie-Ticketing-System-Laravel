<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 15)->unique();
            $table->date('date_of_birth')->nullable();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->enum('gender', ['F', 'M'])->nullable();
            $table->enum('role', ['customer', 'admin'])->default('customer');
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};