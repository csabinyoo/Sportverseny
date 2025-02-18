<?php

use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('users/login', [UsersController::class, 'login']);
Route::post('users/logout', [UsersController::class, 'logout']);
Route::get('users', [UsersController::class, 'index'])
    ->middleware('auth:sanctum');

Route::get('users/{id}', [UsersController::class, 'show'])
    ->middleware('auth:sanctum');
Route::post('users', [UsersController::class, 'store']);
Route::patch('users/{id}', [UsersController::class, 'update'])
    ->middleware('auth:sanctum');
Route::delete('users/{id}', [UsersController::class, 'destroy'])
    ->middleware('auth:sanctum');

// COMPETITIONS

Route::get('competitions', [CompetitionController::class, 'index'])
    ->middleware('auth:sanctum');
Route::get('competitions/{id}', [CompetitionController::class, 'show'])
    ->middleware('auth:sanctum');
Route::post('competitions', [CompetitionController::class, 'store'])
    ->middleware('auth:sanctum');
Route::patch('competitions/{id}', [CompetitionController::class, 'update'])
    ->middleware(middleware: 'auth:sanctum');
Route::delete('competitions/{id}', [CompetitionController::class, 'destroy'])
    ->middleware('auth:sanctum');