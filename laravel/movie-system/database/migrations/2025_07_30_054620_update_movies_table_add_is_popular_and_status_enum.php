<?php
//Name: HO YI VON
//Student ID : 23WMR14542
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::table('movies', function (Blueprint $table) {
        // Add new column
        $table->boolean('is_popular')->default(false);

        // Modify enum (this requires raw SQL in most cases)
        DB::statement("ALTER TABLE movies MODIFY status ENUM('coming_soon', 'now_showing', 'archived', 're_released')");
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('movies', function (Blueprint $table) {
        $table->dropColumn('is_popular');

        // Revert enum
        DB::statement("ALTER TABLE movies MODIFY status ENUM('coming_soon', 'now_showing', 'archived', 're_released)");
    });
    }
};
