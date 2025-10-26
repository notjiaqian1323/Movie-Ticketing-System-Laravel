<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained('halls')->onDelete('cascade');
            $table->char('row_char', 1);
            $table->integer('seat_number');
            $table->enum('status', ['active', 'inactive'])->nullable();
            $table->unique(['hall_id', 'row_char', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
