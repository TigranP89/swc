<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\EventController;

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

Route::middleware("auth:sanctum")->group(function(){
    Route::apiResource('events', EventController::class);
    Route::post('/participate', [EventController::class, 'participate'])->name('api.participate');
    Route::post('/cancel_event', [EventController::class, 'cancel_event'])->name('api.cancel_event');
});

Route::post("/login",[SystemController::class,'login'])->name('api.login');
Route::post("/register",[SystemController::class,'register'])->name('api.register');

