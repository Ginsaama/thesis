<?php

use App\Http\Controllers\chartController;
use App\Http\Controllers\dataBaseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MQTTController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\driverController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TransactionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Publishing Messages in MQTT
Route::post('/publish-message', [MQTTController::class, 'publishMessage']);
// Subsribing to the MQTT topic
Route::get('/receive-messages', [MQTTController::class, 'receiveMessage']);
// Subscribing to Ratings
Route::get('/receive-ratings', [MQTTController::class, 'receiveRating']);
// Bar Chart
Route::get('/bar-chart', [chartController::class, 'barChart']);

Route::get('/dashboard', function () {
    return view('adminDashboard');
});
// Saving to DB
Route::post('/save-to-db', [dataBaseController::class, 'saveToDb']);
// Passengers
// routes/web.php or routes/api.php
Route::post('/get-or-save-passenger', [dataBaseController::class, 'getOrSaveToDB']);

Route::get('/start', function () {
    return view('start');
});
Route::get('/test', function () {
    return view('test');
});


// Login
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('admin.login');
    Route::post('/login', [LoginController::class, 'login']);
    // other admin routes...
});

// RFID
Route::get('/get-driver-info/{rfid}', [driverController::class, 'getDriverInfo'])->name('get-driver-info');

// Code ni Russel

Route::get('/dashboard', [DashboardController::class, 'getStatsPreview'])->name('layouts.dashboard');

Route::get('/passengers', [PassengerController::class, 'index'])->name('layouts.passengers');
Route::get('/analytics', [AnalyticsController::class, 'analytics'])->name('layouts.analytics');

Route::get('/drivers', [DriverController::class, 'index'])->name('layouts.drivers');
Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
Route::get('/drivers/{id}', [DriverController::class, 'show'])->name('drivers.show'); // Driver's Details
Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');

Route::get('/drivers/{id}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
Route::put('drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');
Route::delete('/drivers/{id}',  [DriverController::class, 'delete'])->name('drivers.delete');

Route::get('/transactions/passengers', [TransactionController::class, 'getPassengersTransaction'])->name('transactions.passenger');

Route::get('/transactions/drivers', [TransactionController::class, 'getDriversTransaction'])->name('transactions.driver');
Route::get('/transactions/drivers/create-payment', [TransactionController::class, 'index'])->name('transactions.create-payment');
Route::post('/transactions/drivers', [TransactionController::class, 'storeDriverPayment'])->name('transactions.storeDriverPayment');
Route::put('/transactions/drivers/{id}', [TransactionController::class, 'updatePayments'])->name('transactions.update-payments');

// Route Dates
Route::get('/filter-payments', [TransactionController::class, 'filterPayments'])->name('filterPayments');
