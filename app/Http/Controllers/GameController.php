<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function getAllGames()
    {
        try {
            Log::info('Getting all games');
            $games = DB::table('games')->select('name')->get()->toArray();

            return response()->json([
                'success' => true,
                'message' => "Games retrieved successfull",
                'data' => $games,
            ]);
        } catch (\Exception $exception) {
            Log::info('Error getting games' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting games"

                ],
                500
            );
        }
    }

    public function getGameById($id)
    {
        try {
            Log::info('Getting game by id');
            $game = Game::query()->find($id);
            $channelsGame = Game::query()->find($id)->channels->name;


            if(!$game){
                return response()->json([
                    'success' => true,
                    'message' => "Game not found",
                ],
                404
            );
            }
            
            return response()->json([
                'success' => true,
                'message' => "Game retrieved successfull",
                'data' => $game, $channelsGame,
            ]);
        } catch (\Exception $exception) {
            Log::info('Error getting game' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting game"

                ],
                500
            );
        }
    }

    public function createGame(Request $request)
    {
        try {
            Log::info('Creating game');
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => $validator->errors()
                    ],
                    400
                );
            };
            $newGame = Game::create([
                'name' => $request->input('name'),
                'user_id' => auth()->user()->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Game created successfull",
                'data' => $newGame,
            ]);
        } catch (\Exception $exception) {
            Log::info('Error creating the game' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error creating the game"

                ],
                500
            );
        }
    }

    public function deleteGameById($id)
    {
        try {
            Log::info('Deleting game');
            $game = Game::query()->find($id);
            $game->delete();

            return response()->json([
                'success' => true,
                'message' => "Game deleted succesfull",
            ]);
        } catch (\Throwable $exception) {
            Log::info('Error deleting game' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error deleting game"

                ],
                500
            );
        }
    }
}
