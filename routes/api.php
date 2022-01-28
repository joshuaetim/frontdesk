<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VisitorController;

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

Route::get('/errors', [HomeController::class, 'errors']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/recover-password', [ResetPasswordController::class, 'sendMail']);

Route::post('/recover-password/verify', [ResetPasswordController::class, 'verifyToken']);

Route::post('/recover-password/reset', [ResetPasswordController::class, 'resetPassword']);

Route::middleware(['auth:sanctum'])->group(function() {

    Route::get('/staff', [StaffController::class, 'index']);

    Route::get('/staff/count', [StaffController::class, 'count']);

    Route::get('/staff/populate', [StaffController::class, 'populate']);

    Route::get('/staff/{staff}', [StaffController::class, 'show']);

    Route::post('/staff', [StaffController::class, 'store']);

    Route::put('/staff/{staff}', [StaffController::class, 'update']);

    Route::delete('/staff/{staff}', [StaffController::class, 'destroy']);
    
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('visitors')->group(function(){

        Route::get('/', [VisitorController::class, 'index']);

        Route::get('/count', [VisitorController::class, 'count']);

        Route::get('/populate', [VisitorController::class, 'populate']);

        Route::get('/{visitor}', [VisitorController::class, 'show']);

        Route::post('/', [VisitorController::class, 'store']);

        Route::put('/{visitor}', [VisitorController::class, 'update']);

        Route::delete('/{visitor}', [VisitorController::class, 'destroy']);

    });
});

Route::prefix('admin')->group(function() {

    Route::prefix('/users')->group(function(){

        Route::get('/', [UserController::class, 'getAllUsers']);

        Route::get('/{user}', [UserController::class, 'getUser']);

        Route::post('/{user}/status', [UserController::class, 'changeUserStatus']);

        Route::put('/{user}', [UserController::class, 'updateUser']);

        Route::delete('/{user}', [UserController::class, 'deleteUser']);

    });
});

Route::get('/redis', [HomeController::class, 'redis']);