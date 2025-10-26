<?php
//Name: HO YI VON
//Student ID : 23WMR14542
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->timestamps(); // Adds both created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropTimestamps(); // Removes both created_at and updated_at
        });
    }
};

