<?php

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
        Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('checked_in_at')->nullable()->after('status');
        });
        
        // Update enum to include USED status
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('AVAILABLE', 'BOOKED', 'SOLD', 'USED', 'CANCELLED') DEFAULT 'AVAILABLE'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('checked_in_at');
        });
        
        // Revert enum
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('AVAILABLE', 'BOOKED', 'SOLD', 'CANCELLED') DEFAULT 'AVAILABLE'");
    }
};
