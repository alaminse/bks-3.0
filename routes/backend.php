<?php

use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\CompanyProfitController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DepositController;
use App\Http\Controllers\Backend\FeaturedImageController;
use App\Http\Controllers\Backend\LoginController;
use App\Http\Controllers\Backend\PackageController;
use App\Http\Controllers\Backend\PartnerShareController;
use App\Http\Controllers\Backend\ReferralSettingController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\TaskController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WithdrawController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

// Admin Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-demo', [UserController::class, 'toggleDemo'])->name('users.toggle-demo');

    Route::resource('companies', CompanyController::class);

    Route::post('companies/{company}/update-share-price', [CompanyController::class, 'updateSharePrice'])
        ->name('companies.update-share-price');
    Route::get('companies/{company}/price-history', [CompanyController::class, 'sharePriceHistory'])
        ->name('companies.price-history');

    Route::resource('profits', CompanyProfitController::class);
    Route::post('profits/{profit}/distribute', [CompanyProfitController::class, 'distribute'])
        ->name('profits.distribute');
    Route::get('partner/{user}/distributions', [CompanyProfitController::class, 'partnerDistributions'])
        ->name('profits.partner-distributions');
    // Route::resource('profits', CompanyProfitController::class);
    // Route::post('profits/{profit}/distribute', [CompanyProfitController::class, 'distribute'])
    //     ->name('profits.distribute');
    Route::resource('partner-shares', PartnerShareController::class);
    // ===== ADMIN ROUTES (Deposit/Withdraw Management) =====
    // Deposit Management
    Route::controller(DepositController::class)
        ->prefix('deposits')
        ->name('deposits.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}/show', 'show')->name('show');
            Route::post('/{id}/approve', 'approve')->name('approve');
            Route::post('/{id}/reject', 'reject')->name('reject');
        });

    // Withdrawal Management
    Route::controller(WithdrawController::class)
        ->prefix('withdrawals')
        ->name('withdrawals.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/approve', 'approve')->name('approve');
            Route::post('/{id}/reject', 'reject')->name('reject');
            Route::get('/{id}', 'show')->name('show');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

    // Task Submissions Management
    Route::controller(TaskController::class)
        ->prefix('tasks')
        ->name('tasks.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{task}/edit', 'edit')->name('edit');
            Route::put('{task}', 'update')->name('update');
            Route::delete('{task}', 'destroy')->name('destroy');

            // Assign tasks
            Route::get('assign', 'assignPage')->name('assign');
            Route::post('assign', 'assignToPackage')->name('assign.store');
            Route::post('remove', 'removeFromPackage')->name('remove');
            // Inside the manage group
            Route::get('assign/{package}', 'assignEdit')->name('assign.edit');

            Route::get('/submissions', 'submissions')->name('submissions');
            Route::post('/{id}/approve', 'approve')->name('approve');
            Route::post('/{id}/reject', 'reject')->name('reject');
        });

    // Package Management
    Route::controller(PackageController::class)
        ->prefix('packages')
        ->name('packages.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::get('/{id}/show', 'show')->name('show');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('status/{id}', 'status')->name('status');
        });

    Route::controller(ContactController::class)
        ->prefix('contacts')
        ->name('contacts.')
        ->group(function () {
            // List all contact messages
            Route::get('/', 'index')->name('index');
            // Show single contact message
            Route::get('/{id}', 'show')->name('show');
            // Show reply form
            Route::get('/{id}/reply', 'reply')->name('reply');
            // Send reply
            Route::post('/{id}/reply', 'sendReply')->name('send-reply');
            // Mark as read
            Route::post('/{id}/mark-read', 'markRead')->name('mark-read');
            // Delete message
            Route::delete('/{id}', 'destroy')->name('destroy');
            // Bulk actions
            Route::post('/bulk/delete', 'bulkDelete')->name('bulk-delete');
            Route::post('/bulk/mark-read', 'bulkMarkRead')->name('bulk-mark-read');
        });

    Route::resource('images', FeaturedImageController::class);

    Route::controller(ReferralSettingController::class)
        ->prefix('referrals')
        ->name('referrals.')
        ->group(function () {
            Route::get('settings', 'index')->name('settings');
            Route::post('settings/update', 'update')->name('settings.update');
            Route::post('settings/add-gen', 'addGeneration')->name('settings.add-generation');
            Route::delete('settings/{referralSetting}', 'deleteGeneration')->name('settings.delete-generation');
            Route::get('earnings', 'earnings')->name('earnings');
        });
});
