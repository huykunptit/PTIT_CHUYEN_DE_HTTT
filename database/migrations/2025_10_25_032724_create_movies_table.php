<?php

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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('poster')->nullable();
            $table->string('trailer_url')->nullable();
            $table->integer('duration'); // in minutes
            $table->string('genre');
            $table->string('country');
            $table->string('language');
            $table->integer('age_rating'); // 0, 13, 16, 18
            $table->date('release_date');
            $table->date('end_date');
            $table->enum('status', ['COMING_SOON', 'NOW_SHOWING', 'ENDED'])->default('COMING_SOON');
            $table->decimal('rating', 3, 1)->default(0); // 0.0 to 10.0
            $table->text('cast')->nullable(); // JSON string of cast names
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
