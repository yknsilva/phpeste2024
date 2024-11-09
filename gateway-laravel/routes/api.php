<?php

use App\Http\Controllers\PaymentsController;
use Illuminate\Support\Facades\Route;

Route::controller(PaymentsController::class)->group(function () {
    Route::post('/charge', 'charge');
    Route::post('/capture', 'capture');
    Route::post('/cancel', 'cancel');
    Route::post('/refund', 'refund');
});
