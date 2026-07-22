<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Models\Booking;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.post');

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/standard-rooms', function () {
    $bookedRooms = \App\Models\Booking::where('approval_status', '!=', 'Rejected')
        ->whereDate('booking_date', '>=', now()->toDateString())
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->room_name => ['date' => $item->booking_date, 'time' => $item->end_time]];
        })->toArray();
    return view('standard-rooms', compact('bookedRooms'));
})->name('standard.rooms');

Route::get('/booking-form', function () {
    $roomId = request('room', '1');
    return view('booking-form', compact('roomId'));
})->name('booking.form.full');

Route::get('/advance-rooms', function () {
    $bookedRooms = \App\Models\Booking::where('approval_status', '!=', 'Rejected')
        ->whereDate('booking_date', '>=', now()->toDateString())
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->room_name => ['date' => $item->booking_date, 'time' => $item->end_time]];
        })->toArray();
    return view('advance-rooms', compact('bookedRooms'));
})->name('advance.rooms');

Route::get('/conference-rooms', function () {
    $bookedRooms = \App\Models\Booking::where('approval_status', '!=', 'Rejected')
        ->whereDate('booking_date', '>=', now()->toDateString())
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->room_name => ['date' => $item->booking_date, 'time' => $item->end_time]];
        })->toArray();
    return view('conference-rooms', compact('bookedRooms'));
})->name('conference.rooms');

Route::get('/room-details/{id}', function ($id) {
    // We can pass more context like category to help identify the room
    return view('room-details', ['roomId' => $id, 'category' => request('category')]);
})->name('room.details');

Route::get('/booking', [BookingController::class, 'showBookingForm'])->name('booking.form');
Route::post('/booking', [BookingController::class, 'storeBooking'])->name('booking.store');
Route::post('/contact', [BookingController::class, 'sendSupportMail'])->name('contact.send');

Route::get('/success/{id}', function ($id) {
    $booking = Booking::findOrFail($id);
    return view('success', compact('booking'));
})->name('checkout.success');

Route::get('/receipt/{id}/download', [BookingController::class, 'downloadReceipt'])->name('receipt.download');

Route::get('/failure/{id?}', function ($id = null) {
    return view('failure', compact('id'));
})->name('checkout.failure');

// Admin Auth
Route::get('/admin/login', [LoginController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.post');
Route::post('/admin/logout', [LoginController::class, 'adminLogout'])->name('admin.logout');

// Admin Dashboard Area
use App\Http\Controllers\AdminController;

Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::get('/bookings/export', [AdminController::class, 'exportCsv'])->name('admin.bookings.export');
    Route::get('/bookings/{id}', [AdminController::class, 'show'])->name('admin.bookings.show');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/download', [AdminController::class, 'downloadReport'])->name('admin.reports.download');
    Route::post('/bookings/{id}/approve', [AdminController::class, 'adminApprove'])->name('admin.bookings.approve');
    Route::post('/bookings/{id}/reject', [AdminController::class, 'reject'])->name('admin.bookings.reject');
    Route::post('/bookings/{id}/add-room', [AdminController::class, 'addRoomToBooking'])->name('admin.bookings.add_room');
    Route::post('/bookings/{id}/pay', [AdminController::class, 'markAsPaid'])->name('admin.bookings.pay');
    Route::post('/bookings/{id}/resend-link', [AdminController::class, 'resendPaymentLink'])->name('admin.bookings.resend');
    Route::post('/notifications/mark-read', [AdminController::class, 'markNotificationsRead'])->name('admin.notifications.read');
    Route::delete('/bookings/{id}', [AdminController::class, 'destroy'])->name('admin.bookings.destroy');

    // College Guest Bookings
    Route::get('/college-guest', [AdminController::class, 'showCollegeGuestForm'])->name('admin.college-guest');
    Route::post('/college-guest', [AdminController::class, 'storeCollegeGuestBooking'])->name('admin.college-guest.store');
    Route::post('/college-guest/check-availability', [AdminController::class, 'checkAvailability'])->name('admin.college-guest.check-availability');
});

// These routes are now public for one-click approval from email
Route::get('/admin/bookings/{id}/approve', [AdminController::class, 'principalApprove'])->name('admin.bookings.approve.get');
Route::get('/admin/bookings/{id}/reject', [AdminController::class, 'reject'])->name('admin.bookings.reject.get');

Route::get('/admin/bookings/{id}/approve/hod', [BookingController::class, 'hodApprove'])->name('bookings.approve.hod');
Route::get('/admin/bookings/{id}/approve/warden', [BookingController::class, 'wardenApprove'])->name('bookings.approve.warden');
Route::get('/admin/bookings/{id}/reject/hod', [BookingController::class, 'hodReject'])->name('bookings.reject.hod');
Route::get('/admin/bookings/{id}/reject/warden', [BookingController::class, 'wardenReject'])->name('bookings.reject.warden');

// SuperAdmin Auth
Route::get('/superadmin/login', [LoginController::class, 'showSuperAdminLogin'])->name('superadmin.login');
Route::post('/superadmin/login', [LoginController::class, 'superAdminLogin'])->name('superadmin.login.post');
Route::post('/superadmin/logout', [LoginController::class, 'superAdminLogout'])->name('superadmin.logout');

// Unified Logout Route for Shared Header
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// SuperAdmin Area
Route::prefix('superadmin')->middleware('superadmin.auth')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/settings', [SuperAdminController::class, 'settings'])->name('superadmin.settings');
    Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('superadmin.settings.update');

    // Admin Management
    Route::get('/admins', [SuperAdminController::class, 'manageAdmins'])->name('superadmin.admins');
    Route::post('/admins', [SuperAdminController::class, 'storeAdmin'])->name('superadmin.admins.store');
    Route::post('/admins/{id}', [SuperAdminController::class, 'updateAdmin'])->name('superadmin.admins.update');
    Route::delete('/admins/{id}', [SuperAdminController::class, 'deleteAdmin'])->name('superadmin.admins.delete');

    // Payment & Booking Details
    Route::get('/payments', [SuperAdminController::class, 'payments'])->name('superadmin.payments');
    Route::get('/room-history/{room_name}', [SuperAdminController::class, 'roomHistory'])->name('superadmin.room.history');

    // Webhook Management
    Route::get('/webhooks', [SuperAdminController::class, 'webhookSettings'])->name('superadmin.webhooks');
    Route::post('/webhooks', [SuperAdminController::class, 'storeWebhookEndpoint'])->name('superadmin.webhooks.store');
    Route::post('/webhooks/{id}', [SuperAdminController::class, 'updateWebhookEndpoint'])->name('superadmin.webhooks.update');
    Route::delete('/webhooks/{id}', [SuperAdminController::class, 'deleteWebhookEndpoint'])->name('superadmin.webhooks.delete');
    Route::get('/webhooks/logs', [SuperAdminController::class, 'webhookLogs'])->name('superadmin.webhooks.logs');
    Route::post('/webhooks/logs/{id}/retry', [SuperAdminController::class, 'retryWebhook'])->name('superadmin.webhooks.retry');
});

Route::get('/approval-status', function () {
    return view('approval_status');
})->name('approval.status');

// 🎨 DESIGN PREVIEW ROUTE
Route::get('/mail-preview', function () {
    $booking = Booking::first() ?? new Booking([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '+91 9876543210',
        'room_name' => 'Premium Suite',
        'booking_date' => date('Y-m-d'),
        'start_time' => '10:00:00',
        'end_time' => '12:00:00',
        'no_of_persons' => 2,
        'total_price' => 5000
    ]);
    return new App\Mail\BookingNotification($booking);
});

// PayU Payment Routes
Route::get('/pay/{token}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/pay/{token}/process', [PaymentController::class, 'process'])->name('payment.process');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
