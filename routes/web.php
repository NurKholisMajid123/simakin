<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\TugasController;
use App\Http\Controllers\Admin\CleaningRecordController as AdminCleaningRecordController;
use App\Http\Controllers\Admin\RoomAssignmentController;
use App\Http\Controllers\Ob\DashboardController as ObDashboardController;
use App\Http\Controllers\Ob\CleaningRecordController as ObCleaningRecordController;
use App\Http\Controllers\Ob\TaskCompletionController;
use App\Http\Controllers\NotificationController;

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Shared Routes (untuk Admin dan OB)
Route::middleware('auth')->group(function () {
    // Notification Routes - SHARED untuk admin dan OB
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');

        // Support both GET and POST
        Route::match(['get', 'post'], '/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');

        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::post('/delete-all', [NotificationController::class, 'destroyAll'])->name('delete-all'); // TAMBAHKAN INI
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management (OB Accounts)
    Route::resource('users', UserController::class);

    // Ruangan Management
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::put('/ruangan/{ruangan}', [RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('/ruangan/{ruangan}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');

    // Tugas Management
    Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::post('/tugas', [TugasController::class, 'store'])->name('tugas.store');
    Route::put('/tugas/{tugas}', [TugasController::class, 'update'])->name('tugas.update');
    Route::delete('/tugas/{tugas}', [TugasController::class, 'destroy'])->name('tugas.destroy');

    // Room Assignments
    Route::get('/room-assignments', [RoomAssignmentController::class, 'index'])->name('room-assignments.index');
    Route::post('/room-assignments', [RoomAssignmentController::class, 'store'])->name('room-assignments.store');
    Route::delete('/room-assignments/{id}', [RoomAssignmentController::class, 'destroy'])->name('room-assignments.destroy');

    // Cleaning Records (Reports)
    Route::get('/cleaning-records', [AdminCleaningRecordController::class, 'index'])->name('cleaning-records.index');
    Route::get('/cleaning-records/export', [AdminCleaningRecordController::class, 'export'])->name('cleaning-records.export');
    Route::get('/cleaning-records/{id}', [AdminCleaningRecordController::class, 'show'])->name('cleaning-records.show'); // TAMBAHKAN INI
    Route::post('/cleaning-records/assign', [AdminCleaningRecordController::class, 'assignTask'])->name('cleaning-records.assign');
});

// OB Routes
Route::prefix('ob')->name('ob.')->middleware(['auth', 'ob'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [ObDashboardController::class, 'index'])->name('dashboard');

    // Cleaning Records
    Route::get('/cleaning-records', [ObCleaningRecordController::class, 'index'])->name('cleaning-records.index');
    Route::post('/cleaning-records', [ObCleaningRecordController::class, 'store'])->name('cleaning-records.store');
    Route::get('/cleaning-records/{id}', [ObCleaningRecordController::class, 'show'])->name('cleaning-records.show');

    // AJAX Endpoints for real-time updates
    Route::post('/cleaning/{recordId}/task/{taskId}/toggle', [TaskCompletionController::class, 'toggleTaskComplete'])
        ->name('task.toggle');
    Route::post('/cleaning/{id}/toggle-room', [TaskCompletionController::class, 'toggleRoomCleaned'])
        ->name('room.toggle');
});
