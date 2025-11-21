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
        if (Schema::hasTable('movies')) {
            Schema::table('movies', function (Blueprint $table) {
                $table->index('status');
                $table->index('is_featured');
                $table->index('release_date');
                $table->index(['status', 'is_featured']);
            });
        }

        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index('status');
                $table->index('booking_code');
                $table->index('user_id');
                $table->index('showtime_id');
                $table->index('created_at');
                $table->index(['status', 'created_at']);
            });
        }

        if (Schema::hasTable('showtimes')) {
            Schema::table('showtimes', function (Blueprint $table) {
                $table->index('movie_id');
                $table->index('room_id');
                $table->index('date');
                $table->index('status');
                $table->index(['movie_id', 'date']);
                $table->index(['date', 'status']);
            });
        }

        if (Schema::hasTable('promotions')) {
            Schema::table('promotions', function (Blueprint $table) {
                $table->index('code');
                $table->index('is_active');
                $table->index('start_date');
                $table->index('end_date');
                $table->index(['is_active', 'start_date', 'end_date']);
            });
        }

        if (Schema::hasTable('cinemas')) {
            Schema::table('cinemas', function (Blueprint $table) {
                $table->index('is_active');
            });
        }

        if (Schema::hasTable('rooms')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->index('cinema_id');
                $table->index('is_active');
                $table->index(['cinema_id', 'is_active']);
            });
        }

        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->index('booking_id');
                $table->index('status');
                $table->index('ticket_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('movies')) {
            Schema::table('movies', function (Blueprint $table) {
                $table->dropIndex('movies_status_index');
                $table->dropIndex('movies_is_featured_index');
                $table->dropIndex('movies_release_date_index');
                $table->dropIndex('movies_status_is_featured_index');
            });
        }

        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropIndex('bookings_status_index');
                $table->dropIndex('bookings_booking_code_index');
                $table->dropIndex('bookings_user_id_index');
                $table->dropIndex('bookings_showtime_id_index');
                $table->dropIndex('bookings_created_at_index');
                $table->dropIndex('bookings_status_created_at_index');
            });
        }

        if (Schema::hasTable('showtimes')) {
            Schema::table('showtimes', function (Blueprint $table) {
                $table->dropIndex('showtimes_movie_id_index');
                $table->dropIndex('showtimes_room_id_index');
                $table->dropIndex('showtimes_date_index');
                $table->dropIndex('showtimes_status_index');
                $table->dropIndex('showtimes_movie_id_date_index');
                $table->dropIndex('showtimes_date_status_index');
            });
        }

        if (Schema::hasTable('promotions')) {
            Schema::table('promotions', function (Blueprint $table) {
                $table->dropIndex('promotions_code_index');
                $table->dropIndex('promotions_is_active_index');
                $table->dropIndex('promotions_start_date_index');
                $table->dropIndex('promotions_end_date_index');
                $table->dropIndex('promotions_is_active_start_date_end_date_index');
            });
        }

        if (Schema::hasTable('cinemas')) {
            Schema::table('cinemas', function (Blueprint $table) {
                $table->dropIndex('cinemas_is_active_index');
            });
        }

        if (Schema::hasTable('rooms')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->dropIndex('rooms_cinema_id_index');
                $table->dropIndex('rooms_is_active_index');
                $table->dropIndex('rooms_cinema_id_is_active_index');
            });
        }

        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropIndex('tickets_booking_id_index');
                $table->dropIndex('tickets_status_index');
                $table->dropIndex('tickets_ticket_code_index');
            });
        }
    }
};

