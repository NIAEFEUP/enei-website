<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestApiController;
use Illuminate\Support\Facades\DB;
use App\Models\Participant;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth.token')->post('/quest/{quest}/give', [QuestApiController::class, 'give']);

Route::prefix('payment/mbway')->group(function () {
    Route::post('/', [PaymentController::class, 'initiatePayment'])->name('payment.mbway.initiate');
    Route::get('/status', [PaymentController::class, 'checkPaymentStatus'])->name('payment.mbway.status');
});