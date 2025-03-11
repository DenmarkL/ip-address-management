<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IPAddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public Routes (No Authentication Required)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/refresh', [AuthController::class, 'refresh']);

// Protected Routes (Requires Authentication)
Route::middleware(['auth:api', 'audit.log'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/ip-addresses', [IPAddressController::class, 'index']);
    Route::post('/ip-addresses', [IPAddressController::class, 'store']);
    Route::put('/ip-addresses/{id}', [IPAddressController::class, 'update']);
    Route::delete('/ip-addresses/{id}', [IPAddressController::class, 'destroy']);

    Route::middleware('admin')->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index']);
    });
});
