<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NumberController;
use App\Http\Controllers\MyNumberController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminNumberController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminWebhookLogController;
use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminContactMethodController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\TelegramBotController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public Routes
Route::get('/numbers', [NumberController::class, 'index'])->name('numbers.index');
Route::get('/numbers/{number}', [NumberController::class, 'show'])->name('numbers.show');
Route::get('/contact', function () {
    $contactMethods = \App\Models\ContactMethod::active()->ordered()->get();
    return view('contact', compact('contactMethods'));
})->name('contact');

// Authenticated Routes
Route::middleware(['auth', 'maintenance.mode'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::put('/profile/change-password', [AuthController::class, 'changePassword'])->name('password.update');

    // Purchase Number
    Route::post('/numbers/{number}/purchase', [NumberController::class, 'purchase'])->name('numbers.purchase');

    // My Numbers
    Route::get('/dashboard/numbers', [MyNumberController::class, 'index'])->name('my-numbers.index');
    Route::get('/dashboard/numbers/{purchase}', [MyNumberController::class, 'show'])->name('my-numbers.show');

    // Inbox
    Route::get('/dashboard/numbers/{purchase}/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::get('/dashboard/numbers/{purchase}/inbox/{message}', [InboxController::class, 'show'])->name('inbox.show');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit')->middleware('throttle:10,1');
    Route::get('/wallet/deposit/return', [WalletController::class, 'depositReturn'])->name('wallet.deposit.return');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // Telegram Services
    Route::get('/telegram', [TelegramController::class, 'index'])->name('telegram.index');
    Route::post('/telegram/submit', [TelegramController::class, 'submit'])->name('telegram.submit');
    Route::get('/telegram/history', [TelegramController::class, 'history'])->name('telegram.history');
    Route::get('/telegram/result/{request}', [TelegramController::class, 'result'])->name('telegram.result');

    // Market Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
    Route::post('/services/{service:slug}/purchase', [ServiceController::class, 'purchase'])->name('services.purchase');
    Route::get('/services/purchase/{purchase}', [ServiceController::class, 'result'])->name('services.result');
    Route::get('/services/history', [ServiceController::class, 'history'])->name('services.history');
});

// Webhook Routes (no auth, validated by signature)
Route::post('/api/webhooks/phone/messages', [WebhookController::class, 'phoneMessages']);
Route::post('/api/webhooks/telegram', [WebhookController::class, 'telegram']);
Route::post('/api/webhooks/payments/deposit', [WalletController::class, 'depositCallback']);

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/adjust-balance', [AdminUserController::class, 'adjustBalance'])->name('users.adjust-balance');

    // Numbers
    Route::get('/numbers', [AdminNumberController::class, 'index'])->name('numbers.index');
    Route::get('/numbers/create', [AdminNumberController::class, 'create'])->name('numbers.create');
    Route::post('/numbers', [AdminNumberController::class, 'store'])->name('numbers.store');
    Route::get('/numbers/{number}/edit', [AdminNumberController::class, 'edit'])->name('numbers.edit');
    Route::put('/numbers/{number}', [AdminNumberController::class, 'update'])->name('numbers.update');
    Route::delete('/numbers/{number}', [AdminNumberController::class, 'destroy'])->name('numbers.destroy');
    Route::get('/numbers/purchases', [AdminNumberController::class, 'purchases'])->name('numbers.purchases');

    // Payments
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');

    // Services
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [AdminServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');

    // Contact Methods
    Route::get('/contact-methods', [AdminContactMethodController::class, 'index'])->name('contact-methods.index');
    Route::get('/contact-methods/create', [AdminContactMethodController::class, 'create'])->name('contact-methods.create');
    Route::post('/contact-methods', [AdminContactMethodController::class, 'store'])->name('contact-methods.store');
    Route::get('/contact-methods/{contactMethod}/edit', [AdminContactMethodController::class, 'edit'])->name('contact-methods.edit');
    Route::put('/contact-methods/{contactMethod}', [AdminContactMethodController::class, 'update'])->name('contact-methods.update');
    Route::delete('/contact-methods/{contactMethod}', [AdminContactMethodController::class, 'destroy'])->name('contact-methods.destroy');

    // Webhook Logs
    Route::get('/webhook-logs', [AdminWebhookLogController::class, 'index'])->name('webhooks.index');
    Route::get('/webhook-logs/{log}', [AdminWebhookLogController::class, 'show'])->name('webhooks.show');

    // Audit Logs
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');

    // Settings (KV store / system requirements & initial data)
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Telegram Bot management
    Route::get('/telegram/bot', [TelegramBotController::class, 'index'])->name('telegram.bot.index');
    Route::post('/telegram/bot/webhook', [TelegramBotController::class, 'setWebhook'])->name('telegram.bot.webhook');
    Route::delete('/telegram/bot/webhook', [TelegramBotController::class, 'deleteWebhook'])->name('telegram.bot.webhook.delete');
    Route::post('/telegram/bot/test', [TelegramBotController::class, 'testMessage'])->name('telegram.bot.test');
});
