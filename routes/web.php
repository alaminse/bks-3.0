<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserCompanyController;


Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/packages/{package}', [WelcomeController::class, 'packageShow'])->name('packages.details');
Route::post('/contact', [WelcomeController::class, 'contactStore'])->name('contact.store');


Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(WalletController::class)
        ->prefix('wallet')
        ->name('wallet.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/transactions', 'transactions')->name('transactions');
            // Balance API (for AJAX)
            Route::get('/balance', 'balance')->name('balance');
             // ===== DEPOSIT ROUTES =====
            Route::get('/deposit', [DepositController::class, 'deposit'])->name('deposit');
            Route::post('/deposit/store', [DepositController::class, 'store'])->name('deposit.store');
        });

    // ===== WITHDRAWAL ROUTES =====
    Route::controller(WithdrawController::class)
        ->prefix('withdraw')
        ->name('withdraw.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
        });

    // ===== PACKAGES ROUTES =====
    Route::controller(PackageController::class)
        ->prefix('packages')
        ->name('packages.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/details/{slug}', 'show')->name('show');
            Route::post('/{slug}/purchase', 'purchase')->name('purchase');
            Route::get('/my/active', 'myPackages')->name('my');
        });

    // ===== TASK ROUTES =====
    Route::controller(TaskController::class)
        ->prefix('tasks')
        ->name('tasks.')
        ->group(function () {
            // Available tasks
            Route::get('/', 'index')->name('index');
            // Submit task
            Route::post('/submit', 'submit')->name('submit');
            // Task history
            Route::get('/history', 'history')->name('history');
            Route::post('/auto-verify', 'autoVerify')->name('auto-verify');
            Route::get('/ad/{task}', 'viewAd')->name('ad.view');
        });

        // Referral routes (protected)
        Route::controller(ReferralController::class)
            ->prefix('referrals')
            ->name('referrals.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/link', 'getReferralLink')->name('link');
                Route::post('/copy', 'copyLink')->name('referrals.copy');
            });

        Route::controller(ProfileController::class)
            ->prefix('profile')
            ->name('profile.')
            ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/basic-info', 'updateBasicInfo')->name('update.basic');
            Route::post('/details', 'update')->name('update.details');
            Route::post('/social-links', 'updateSocialLinks')->name('update.social');
            Route::post('/avatar', 'uploadAvatar')->name('upload.avatar');
            Route::post('/password', 'changePassword')->name('change.password');
            Route::delete('/delete', 'deleteAccount')->name('delete');
        });

        Route::controller(UserCompanyController::class)
            ->prefix('companies')
            ->name('companies.')
            ->group(function () {

                // Static routes first
                Route::get('/my-investments', 'myInvestments')->name('my-investments');
                Route::get('/profit-history', 'profitHistory')->name('profit-history');
                Route::get('/portfolio', 'portfolio')->name('portfolio');

                // Company browsing and investment
                Route::get('/', 'index')->name('index');
                Route::get('/{company}', 'show')->name('show');
                Route::get('/{company}/invest', 'invest')->name('invest');
                Route::post('/{company}/invest', 'processInvestment')->name('process-investment');

        });
    });
