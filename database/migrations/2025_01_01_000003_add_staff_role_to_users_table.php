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
        if (Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'user') DEFAULT 'user'");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $column = $table->enum('role', ['admin', 'staff', 'user'])->default('user');
                if (Schema::hasColumn('users', 'is_member')) {
                    $column->after('is_member');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') DEFAULT 'user'");
        }
    }
};

