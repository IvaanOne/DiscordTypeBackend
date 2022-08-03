<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;
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

// Route Bienvenida

Route::get('/', function() {
    return "Bienvenido";
});

// AUTH ROUTES -------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// USER ROUTES ------------
Route::group(
    ['middleware' => 'jwt.auth'], 
    function (){
    Route::get('/profile',  [AuthController::class, 'me']);
    Route::delete('/logout',  [AuthController::class, 'logout']);
});

// USER ADMIN ROUTES --------
Route::group(
    ['middleware' => 'jwt.auth','isSuperAdmin'],
    function (){
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::get('/users/{id}', [UserController::class, 'getUserById']);
});

// GAMES ROUTES -------------
Route::group(
    ['middleware' => 'jwt.auth','isSuperAdmin'], 
    function (){
    Route::post('/createGame',  [GameController::class, 'createGame']);
    Route::delete('/deleteGame/{id}',  [GameController::class, 'deleteGameById']);
    Route::get('/game/{id}',  [GameController::class, 'getGameById']);
    Route::get('/games}',  [GameController::class, 'getAllGames']);
    
});

Route::get('/games', [GameController::class, 'getAllGames']);

// CHANNELS ROUTES -----------

// MESSAGES ROUTES -----------

Route::group(
    [],
    function (){
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::get('/users/{id}', [UserController::class, 'getUserById']);
});
