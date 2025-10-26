<?php
//Name: Wo Jia Qian
//Student Id: 2314023

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('seat_id')->nullable()->constrained('seats')->onDelete('set null');
            $table->enum('ticket_type', ['ADULT', 'CHILD', 'SENIOR'])->default('ADULT');
            $table->decimal('price', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_seats');
    }
};
