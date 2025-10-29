<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MovieController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\MovieApiController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/booking/{showtime}', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/payment/{booking}', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment/vnpay', [PaymentController::class, 'vnpay'])->name('payment.vnpay');
Route::get('/payment/vnpay/return', [PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('movies', AdminMovieController::class);
    Route::resource('cinemas', CinemaController::class);
    Route::resource('showtimes', ShowtimeController::class);
    Route::resource('bookings', AdminBookingController::class);
});

// API Routes
Route::prefix('api/movies')->name('api.movies.')->group(function () {
    Route::get('/now-playing', [MovieApiController::class, 'nowPlaying'])->name('now-playing');
    Route::get('/upcoming', [MovieApiController::class, 'upcoming'])->name('upcoming');
    Route::get('/popular', [MovieApiController::class, 'popular'])->name('popular');
    Route::get('/search', [MovieApiController::class, 'search'])->name('search');
    Route::get('/genres', [MovieApiController::class, 'genres'])->name('genres');
    Route::get('/genre/{genreId}', [MovieApiController::class, 'byGenre'])->name('by-genre');
    Route::get('/{movieId}', [MovieApiController::class, 'show'])->name('show');
    Route::post('/clear-cache', [MovieApiController::class, 'clearCache'])->name('clear-cache');
});
