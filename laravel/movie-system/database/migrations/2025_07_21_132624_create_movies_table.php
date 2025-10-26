<?php
//Name: HO YI VON
//Student ID : 23WMR14542

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('genre', 50);
            $table->string('director', 255);
            $table->text('cast');
            $table->text('synopsis')->nullable();
            $table->integer('duration')->nullable();
            $table->string('language', 50)->nullable();
            $table->string('subtitles', 50)->nullable();
            $table->string('age_rating', 10)->nullable();
            $table->enum('status', ['coming_soon', 'now_showing', 'archived']); 
            $table->date('release_date');
            $table->string('image_url', 255)->nullable();
            $table->decimal('avg_review', 2, 1)->default(0.0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
