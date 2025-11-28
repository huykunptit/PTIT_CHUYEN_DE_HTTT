<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MovieController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\CheckInController;
use App\Http\Controllers\Frontend\TicketController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\PhoneAuthController;
use App\Http\Controllers\Api\MovieApiController;
use App\Http\Controllers\Api\MovieSearchController;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/login/phone', [PhoneAuthController::class, 'showPhoneLoginForm'])->name('login.phone');
Route::post('/login/phone/send', [PhoneAuthController::class, 'sendOtp'])->name('login.phone.send');
Route::get('/login/phone/verify', [PhoneAuthController::class, 'showVerifyForm'])->name('login.phone.verify');
Route::post('/login/phone/verify', [PhoneAuthController::class, 'verifyOtp'])->name('login.phone.verify.post');

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');

Route::middleware('auth')->group(function () {
    Route::get('/booking/{showtime}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});
Route::get('/payment/{booking}', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment/{booking}/vnpay', [PaymentController::class, 'vnpay'])->name('payment.vnpay');
Route::get('/payment/demo/{booking}', [PaymentController::class, 'demo'])->name('payment.demo');
Route::match(['get', 'post'], '/payment/demo/{booking}/process', [PaymentController::class, 'processDemo'])->name('payment.demo.process');
Route::get('/payment/vnpay/return', [PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');
Route::post('/payment/vnpay/ipn', [PaymentController::class, 'vnpayIpn'])->name('payment.vnpay.ipn');

// Check-in Routes
Route::get('/check-in', [CheckInController::class, 'show'])->name('checkin.show');
Route::post('/check-in', [CheckInController::class, 'checkIn'])->name('checkin.checkin');
Route::get('/check-in/ticket/{ticketCode}', [CheckInController::class, 'ticketInfo'])->name('checkin.ticket-info');

// Ticket Routes (Phát vé) + My Tickets (chỉ cho user đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/tickets/booking/{booking}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}', [TicketController::class, 'detail'])->name('tickets.detail');
    Route::get('/tickets/{ticket}/print', [TicketController::class, 'printPdf'])->name('tickets.print');
    Route::get('/tickets/booking/{booking}/print', [TicketController::class, 'printBookingPdf'])->name('tickets.booking.print');

    Route::get('/my-tickets', [\App\Http\Controllers\Frontend\MyTicketsController::class, 'index'])->name('my-tickets.index');
    Route::post('/my-tickets/{booking}/cancel', [\App\Http\Controllers\Frontend\MyTicketsController::class, 'cancel'])->name('my-tickets.cancel');
});

// Admin Routes (Protected by admin middleware - only admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.only'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/bookings', [\App\Http\Controllers\Admin\ReportController::class, 'bookings'])->name('reports.bookings');
    Route::resource('movies', AdminMovieController::class);
    Route::resource('cinemas', CinemaController::class);
    Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);
    Route::resource('showtimes', ShowtimeController::class);
    Route::resource('bookings', AdminBookingController::class);
    Route::patch('bookings/{booking}/inline-status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
});

// Staff Routes (Protected by staff middleware - admin and staff)
Route::prefix('staff')->name('staff.')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [\App\Http\Controllers\Staff\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Staff\BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [\App\Http\Controllers\Staff\BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::get('/check-in', [\App\Http\Controllers\Staff\CheckInController::class, 'show'])->name('checkin.show');
    Route::post('/check-in', [\App\Http\Controllers\Staff\CheckInController::class, 'checkIn'])->name('checkin.checkin');
    Route::get('/check-in/ticket/{ticketCode}', [\App\Http\Controllers\Staff\CheckInController::class, 'ticketInfo'])->name('checkin.ticket-info');
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

// Search Autocomplete API
Route::get('/api/search/autocomplete', [MovieSearchController::class, 'autocomplete'])->name('api.search.autocomplete');

// Notifications API Routes
Route::prefix('api/notifications')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
    Route::get('/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
    Route::post('/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
    Route::post('/mark-all-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
});

// Seat Hold API Routes
Route::prefix('api/seats')->middleware('auth')->group(function () {
    Route::post('/hold', [\App\Http\Controllers\Api\SeatHoldController::class, 'hold']);
    Route::post('/release', [\App\Http\Controllers\Api\SeatHoldController::class, 'release']);
    Route::get('/showtime/{showtimeId}/held', [\App\Http\Controllers\Api\SeatHoldController::class, 'getHeldSeats']);
    Route::post('/check-status', [\App\Http\Controllers\Api\SeatHoldController::class, 'checkStatus']);
});

// Promotion API Routes
Route::prefix('api/promotions')->middleware('auth')->group(function () {
    Route::post('/validate', [\App\Http\Controllers\Api\PromotionController::class, 'validate']);
});

// Broadcasting Auth
Route::post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
})->middleware('auth');
