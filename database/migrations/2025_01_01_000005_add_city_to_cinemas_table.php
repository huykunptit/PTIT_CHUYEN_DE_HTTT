<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cinemas') && !Schema::hasColumn('cinemas', 'city')) {
            Schema::table('cinemas', function (Blueprint $table) {
                $table->string('city', 50)->default('HCM')->after('address');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cinemas') && Schema::hasColumn('cinemas', 'city')) {
            Schema::table('cinemas', function (Blueprint $table) {
                $table->dropColumn('city');
            });
        }
    }
};

