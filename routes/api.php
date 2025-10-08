<?php

use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Users\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\JwtMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function (Request $request) {
    return User::all();
});

Route::post('/login', [AuthController::class, 'login']);

Route::resource('users', UserController::class)
->middleware([JwtMiddleware::class,AdminMiddleware::class])->except('show')
;

Route::fallback(fn () => response(["message" => 'Página não encontrada'], 404));

