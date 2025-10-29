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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['PERCENTAGE', 'FIXED_AMOUNT', 'FREE_TICKET'])->default('PERCENTAGE');
            $table->decimal('value', 10, 2); // percentage or fixed amount
            $table->decimal('min_amount', 10, 2)->nullable(); // minimum order amount
            $table->decimal('max_discount', 10, 2)->nullable(); // maximum discount amount
            $table->integer('usage_limit')->nullable(); // total usage limit
            $table->integer('usage_count')->default(0); // current usage count
            $table->integer('per_user_limit')->default(1); // usage limit per user
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
