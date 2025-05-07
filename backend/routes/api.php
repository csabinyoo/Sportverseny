<?php

use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\MemberResultsAtStationController;
use App\Http\Controllers\ResultTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\TeamAtStationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UsersController;
use App\Models\User;
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

Route::get('competitions', [CompetitionController::class, 'index']);
Route::get('getCurrentComp', [CompetitionController::class, 'getCurrentComp']);
Route::get('competitions/{id}', [CompetitionController::class, 'show']);
Route::post('competitions', [CompetitionController::class, 'store'])
    ->middleware('auth:sanctum');
Route::patch('competitions/{id}', [CompetitionController::class, 'update'])
    ->middleware(middleware: 'auth:sanctum');
Route::delete('competitions/{id}', [CompetitionController::class, 'destroy'])
    ->middleware('auth:sanctum');
Route::patch('competitionCurrentCompToFalse', [CompetitionController::class, 'competitionCurrentCompToFalse'])
    ->middleware('auth:sanctum');

// MEMBERATSTATION

Route::get('memberatstation', [MemberResultsAtStationController::class, 'index']);
Route::get('memberatstation/{id}', [MemberResultsAtStationController::class, 'show']);
Route::post('memberatstation', [MemberResultsAtStationController::class, 'store'])
    ->middleware('auth:sanctum');
Route::patch('memberatstation/{id}', [MemberResultsAtStationController::class, 'update'])
    ->middleware(middleware: 'auth:sanctum');
Route::delete('memberatstation/{id}', [MemberResultsAtStationController::class, 'destroy'])
    ->middleware('auth:sanctum');

// ResultType

Route::get('results', [ResultTypeController::class, 'index']);
Route::get('results/{id}', [ResultTypeController::class, 'show']);
Route::post('results', [ResultTypeController::class, 'store'])
    ->middleware('auth:sanctum');
Route::patch('results/{id}', [ResultTypeController::class, 'update'])
    ->middleware(middleware: 'auth:sanctum');
Route::delete('results/{id}', [ResultTypeController::class, 'destroy'])
    ->middleware('auth:sanctum');

// Roles

Route::get('roles', [RoleController::class, 'index']);
Route::get('roles/{id}', [RoleController::class, 'show']);
Route::post('roles', [RoleController::class, 'store'])
    ->middleware('auth:sanctum');
Route::patch('roles/{id}', [RoleController::class, 'update'])
    ->middleware(middleware: 'auth:sanctum');
Route::delete('roles/{id}', [RoleController::class, 'destroy'])
    ->middleware('auth:sanctum');

// STATIONS

Route::get('stations', [StationController::class, 'index']);
Route::get('stations/{id}', [StationController::class, 'show']);
Route::post('stations', [StationController::class, 'store'])
    ->middleware('auth:sanctum');
Route::patch('stations/{id}', [StationController::class, 'update'])
    ->middleware(middleware: 'auth:sanctum');
Route::delete('stations/{id}', [StationController::class, 'destroy'])
    ->middleware('auth:sanctum');

// TEAM  AT STATION

Route::get('taematstation', [TeamAtStationController::class, 'index']);
Route::get('taematstation/{id}', [TeamAtStationController::class, 'show']);
Route::post('taematstation', action: [TeamAtStationController::class, 'store'])
    ->middleware('auth:sanctum');
Route::patch('taematstation/{id}', [TeamAtStationController::class, 'update'])
    ->middleware(middleware: 'auth:sanctum');
Route::delete('taematstation/{id}', [TeamAtStationController::class, 'destroy'])
    ->middleware('auth:sanctum');

// TEAMS

Route::get('teams', [TeamController::class, 'index']);
Route::get('teams/{id}', [TeamController::class, 'show']);
Route::post('teams', action: [TeamController::class, 'store'])
    ->middleware('auth:sanctum');
Route::patch('teams/{id}', [TeamController::class, 'update'])
    ->middleware(middleware: 'auth:sanctum');
Route::delete('teams/{id}', [TeamController::class, 'destroy'])
    ->middleware('auth:sanctum');

// TEAM MEMBER

Route::get('teammember', [TeamMemberController::class, 'index']);
Route::get('teammember/{id}', [TeamMemberController::class, 'show']);
Route::post('teammember', action: [TeamMemberController::class, 'store'])
    ->middleware('auth:sanctum');
Route::patch('teammember/{id}', [TeamMemberController::class, 'update'])
    ->middleware(middleware: 'auth:sanctum');
Route::delete('teammember/{id}', [TeamMemberController::class, 'destroy'])
    ->middleware('auth:sanctum');

//querrys

Route::get('teamAtStation/{stationId}', [TeamAtStationController::class, 'teamAtStation']);
Route::get('membersResultsAtStation/{teamAtStationId}/{teamId}', [MemberResultsAtStationController::class, 'membersResultsAtStation']);
