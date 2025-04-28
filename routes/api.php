<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LoanController;

Route::get('/api-debug', function () {
    return ['ok' => true];
});
Route::post('/clients', [ClientController::class, 'store']);
Route::post('/loans/check', [LoanController::class, 'check']);
Route::post('/loans', [LoanController::class, 'store']);
