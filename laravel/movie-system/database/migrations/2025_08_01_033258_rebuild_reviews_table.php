<?php
// Name: CHONG CHEE WEE
// Student ID: 2314523
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->tinyInteger('rating')->unsigned()->comment('1-5 star rating');
            $table->text('comment')->nullable();
            $table->timestamp('review_datetime')->useCurrent();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('edited')->default(false);
            $table->timestamps(); // Adds created_at and updated_at
            $table->unique(columns: ['movie_id', 'account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
