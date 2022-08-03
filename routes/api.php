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

// Users Routes
Route::group(
    [],
    function (){
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::get('/users/{id}', [UserController::class, 'getUserById']);
});

// AUTH ROUTES -------
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);


Route::group(
    ['middleware' => 'jwt.auth','isSuperAdmin'], 
    function (){
    Route::post('/createGame',  [GameController::class, 'createGame']);
    Route::delete('/deleteGame/{id}',  [GameController::class, 'deleteGameById']);
});

// GAMES ROUTES ------

Route::group(
    [],
    function (){
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::get('/users/{id}', [UserController::class, 'getUserById']);
});
