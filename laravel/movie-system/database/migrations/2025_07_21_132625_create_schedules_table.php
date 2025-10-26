<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('hall_id')->constrained('halls')->onDelete('cascade');
            $table->dateTime('show_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
