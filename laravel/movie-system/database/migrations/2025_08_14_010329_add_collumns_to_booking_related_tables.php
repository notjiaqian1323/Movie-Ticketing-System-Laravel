<?php
// Name: Wo Jia Qian
// Student Id: 2314023

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
        // Add timestamps to halls and seats tables
        Schema::table('halls', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->timestamps();
        });

        // Add timestamps and a status column to the bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'failed', 'cancelled'])->default('pending')->after('qr_code');
            $table->timestamps();
        });

        // Add timestamps to the booking_seats table
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the changes in reverse order
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropTimestamps();
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('halls', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};
