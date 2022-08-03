<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MessageController;
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
    Route::post('/updateProfile',  [AuthController::class, 'updateProfile']);
    Route::delete('/logout',  [AuthController::class, 'logout']);
    Route::post('/joinChannel/{id}',  [UserController::class, 'joinChannelById']);
    Route::delete('/leftChannel/{id}',  [UserController::class, 'getOutOfAChannelById']);
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
    
});

Route::get('/games', [GameController::class, 'getAllGames']);

// CHANNELS ROUTES -----------
Route::group(
    ['middleware' => 'jwt.auth'], 
    function (){
    Route::get('/channels',  [ChannelController::class, 'getAllChannels']);
    Route::get('/channel/{id}',  [ChannelController::class, 'getMessagesWithChannelById']);
    Route::post('/createChannel',  [ChannelController::class, 'createChannel']);
    Route::put('/updateChannel/{id}',  [ChannelController::class, 'updateChannel']);
    Route::delete('/deleteChannel/{id}',  [ChannelController::class, 'deleteChannelById']);
});
// MESSAGES ROUTES -----------

Route::group(
    ['middleware' => 'jwt.auth'], 
    function (){
    Route::post('/createMessage',  [MessageController::class, 'createMessage']);
    Route::put('/updateMessage/{id}',  [MessageController::class, 'updateMessageById']);
    Route::delete('/deleteMessage/{id}',  [MessageController::class, 'deleteMessageById']);
    });
    
