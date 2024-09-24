<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BillController;

Route::get('/', function () {
    return view('welcome');
});


// Application Routes
Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');

// Bill Routes
Route::get('/applications/{application}/bills/create', [BillController::class, 'create'])->name('bills.create');
Route::post('/applications/{application}/bills', [BillController::class, 'store'])->name('bills.store');
Route::get('/applications/{application}/bills/{bill}', [BillController::class, 'show'])->name('bills.show');
