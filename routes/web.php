<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StartupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FraudRuleController;
use App\Http\Controllers\FraudDetectionController;
use App\Http\Controllers\AnalysisController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //startup
    Route::get('/startup/create', [StartupController::class, 'create'])->name('startup.create');
    Route::post('/startup', [StartupController::class, 'store'])->name('startup.store');
    Route::get('/startup', [StartupController::class, 'show'])->name('startup.show');
    //transaction
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    //Simulations
    Route::post('/simulations', [SimulationController::class, 'store'])->name('simulations.store');
    Route::get('/simulations/{id}', [SimulationController::class, 'show'])->name('simulations.show');
    //Alerts
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/{id}', [AlertController::class, 'show'])->name('alerts.show');
    Route::get('/alerts/{id}/edit', [AlertController::class, 'edit'])->name('alerts.edit');
    Route::put('/alerts/{id}', [AlertController::class, 'update'])->name('alerts.update');
    Route::delete('/alerts/{id}', [AlertController::class, 'destroy'])->name('alerts.destroy');
    //Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');
    //Fraud Rules
    Route::get('/fraud-rules', [FraudRuleController::class, 'index'])->name('fraud_rules.index');
    Route::get('/fraud-rules/{id}/edit', [FraudRuleController::class, 'edit'])->name('fraud_rules.edit');
    Route::put('/fraud-rules/{id}', [FraudRuleController::class, 'update'])->name('fraud_rules.update');
    //FraudDetection
    Route::get('/startups/{startup}/fraud-detection', [FraudDetectionController::class, 'show']);
    //AnalysisCount
    Route::get('/startups/{startup}/analysis', [AnalysisController::class, 'show'])
        ->name('analysis.show');
});

require __DIR__ . '/auth.php';
