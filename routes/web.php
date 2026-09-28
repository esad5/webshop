<?php

use App\Http\Controllers\GuestOrderController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::post('/narudzbe', [GuestOrderController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('orders.store');
