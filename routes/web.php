<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FraudDetectionController;
use App\Http\Controllers\FraudRuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\StartupController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Startups
    |--------------------------------------------------------------------------
    */
    Route::get('/startups', [StartupController::class, 'index'])->name('startups.index'); // FIXED
    Route::get('/startup/create', [StartupController::class, 'create'])->name('startup.create');
    Route::post('/startup', [StartupController::class, 'store'])->name('startup.store');
    Route::get('/startup', [StartupController::class, 'show'])->name('startup.show');

    /*
    |--------------------------------------------------------------------------
    | Transactions
    |--------------------------------------------------------------------------
    */
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    /*
    |--------------------------------------------------------------------------
    | Simulations
    |--------------------------------------------------------------------------
    */
    Route::get('/simulations', [SimulationController::class, 'index'])->name('simulations.index');
    Route::post('/simulations', [SimulationController::class, 'store'])->name('simulations.store');
    Route::get('/simulations/{id}', [SimulationController::class, 'show'])->name('simulations.show');

    /*
    |--------------------------------------------------------------------------
    | Alerts
    |--------------------------------------------------------------------------
    */
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::patch('/alerts/{id}/approve', [AlertController::class, 'approve'])->name('alerts.approve');
    Route::patch('/alerts/{id}/reject', [AlertController::class, 'reject'])->name('alerts.reject');
    Route::patch('/alerts/{id}/confirm-fraud', [AlertController::class, 'confirmFraud'])->name('alerts.confirmFraud');
    Route::patch('/alerts/{id}/false-positive', [AlertController::class, 'markFalsePositive'])->name('alerts.markFalsePositive');

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');

    /*
    |--------------------------------------------------------------------------
    | Fraud Rules
    |--------------------------------------------------------------------------
    */
    Route::get('/fraud-rules', [FraudRuleController::class, 'index'])->name('fraud_rules.index');
    Route::get('/fraud-rules/{id}/edit', [FraudRuleController::class, 'edit'])->name('fraud_rules.edit');
    Route::put('/fraud-rules/{id}', [FraudRuleController::class, 'update'])->name('fraud_rules.update');

    /*
    |--------------------------------------------------------------------------
    | Fraud Detection / Analysis
    |--------------------------------------------------------------------------
    */
    Route::get('/startups/{startup}/fraud-detection', [FraudDetectionController::class, 'show'])->name('fraud-detection.show');
    Route::get('/startups/{startup}/analysis', [AnalysisController::class, 'show'])->name('analysis.show');

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    /*
    |--------------------------------------------------------------------------
    | Admin (Protected)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    });

});

require __DIR__.'/auth.php';