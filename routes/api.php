<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaffController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/home', [HomeController::class, 'home']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function() {

    Route::get('/staff', [StaffController::class, 'index']);

    Route::get('/staff/{staff}', [StaffController::class, 'show']);

    Route::post('/staff', [StaffController::class, 'store']);

    Route::put('/staff/{staff}', [StaffController::class, 'update']);

    Route::delete('/staff/{staff}', [StaffController::class, 'destroy']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/redis', [HomeController::class, 'redis']);