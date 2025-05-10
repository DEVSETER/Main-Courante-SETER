<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('auth/login-token', [UserController::class, 'login'])->name('login-token');
Route::post('auth/login-token', [UserController::class, 'login'])->name('login-token');



Route::post('/login', [UserController::class, 'login']);




// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
