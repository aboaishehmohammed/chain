<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactDetailController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group( function () {
    Route::prefix('contact-detail')->group(function (){
        Route::post('/', [ContactDetailController::class,'store'])->middleware('hr_manager');
        Route::put('/', [ContactDetailController::class,'update'])->middleware('employee');
    });
    Route::middleware('hr_manager')->prefix('employee')->group(function (){
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::put('/', [EmployeeController::class, 'updateStatus']);
    });
        Route::post('auth/logout', [AuthController::class,'logout']);
});

Route::post('/auth/login', [AuthController::class, 'loginUser'])->middleware('guest');
