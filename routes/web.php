<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Mail;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'store']);
});

// Admin Panel Routes (Authenticated + Admin only)
Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // User Management
    Route::resource('users', UserController::class);

    // Contact Management
    Route::get('contacts/sample/csv', [ContactController::class, 'downloadSampleCsv'])->name('contacts.sample.csv');
    Route::get('contacts/import', [ContactController::class, 'import'])->name('contacts.import');
    Route::post('contacts/import', [ContactController::class, 'import'])->name('contacts.import.store');
    Route::resource('contacts', ContactController::class);

    //Email templates
    Route::resource('email-templates', EmailTemplateController::class);
    Route::resource('campaigns', CampaignController::class);
    Route::resource('activityLogs', ActivityLogController::class);

    // Profile Management
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::get('/test-mail', function () {

    Mail::raw('Test Email From Laravel', function ($message) {
        $message->to('er.sukhdevkamboj@gmail.com')
                ->subject('Laravel Gmail SMTP Test');
    });

    return 'Email Sent';
});
