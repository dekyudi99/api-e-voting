<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AcccessCodeController;
use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\VotesController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route untuk voting
Route::post('/vote', [VotesController::class, 'votes']);
Route::post('/check-access-code', [VotesController::class, 'accessCheck']);

// Route untuk menampilkan daftar kandidat
Route::get('/candidates', [CandidatesController::class, 'index']);

// Route untuk hasil voting
Route::get('/results', [VotesController::class, 'results']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->middleware('throttle:1,5');
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route untuk akses kode
    Route::post('/generate-access-code', [AcccessCodeController::class, 'generateCode']);
    Route::get('/list-access-codes', [AcccessCodeController::class, 'listCodes']);
    Route::delete('/delete-access-code/{id}', [AcccessCodeController::class, 'deleteCode']);

    // Route untuk kandidat
    Route::post('/candidates', [CandidatesController::class, 'store']);
    Route::get('/candidates/{id}', [CandidatesController::class, 'show']);
    Route::post('/candidates/{id}', [CandidatesController::class, 'update']);
    Route::delete('/candidates/{id}', [CandidatesController::class, 'destroy']);
});