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
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('day_type', ['WEEKDAY', 'WEEKEND', 'HOLIDAY'])->default('WEEKDAY');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('audience_type', ['ADULT', 'STUDENT', 'CHILD', 'SENIOR', 'MEMBER'])->default('ADULT');
            $table->enum('seat_type', ['STANDARD', 'VIP', 'COUPLE'])->default('STANDARD');
            $table->decimal('base_price', 10, 2);
            $table->decimal('surcharge', 10, 2)->default(0); // for 3D, IMAX, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
