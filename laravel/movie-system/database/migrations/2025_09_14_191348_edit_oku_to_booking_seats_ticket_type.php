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
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->enum('ticket_type', ['ADULT', 'CHILD', 'OKU'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            //
        });
    }
};
