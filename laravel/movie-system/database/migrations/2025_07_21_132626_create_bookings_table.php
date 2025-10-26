<?php
//Name: Wo Jia Qian
//Student Id: 2314023

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->nullable()->constrained('accounts')->onDelete('set null');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->dateTime('booking_time')->useCurrent();
            $table->decimal('total_amount', 10, 2);
            $table->string('qr_code', 255)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
