<?php

use App\Models\Users\User;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Auth\SocialiteController;



Route::post('/login', [AuthControsller::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login/google', [SocialiteController::class, 'handleGoogleCallback']);

Route::middleware([JwtMiddleware::class])->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Route::middleware([AdminMiddleware::class])->group(function () {
    //     Route::resource('/users', UserController::class)
    //         ->withoutMiddleware([AdminMiddleware::class])
    //         ->only('index', 'show');
    // });
  Route::get
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
});

Route::middleware([JwtMiddleware::class, AdminMiddleware::class])->group(function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);


});
Route::get('/export_users', [UserController::class, 'export_users']);

Route::get('/test', function (Request $request) {
    return User::all();
});
Route::fallback(fn() => response(["message" => 'Página não encontrada'], 404));

