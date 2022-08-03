<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function getAllUsers()
    {
        try {
            Log::info('Getting all users');
            $users = User::query()->get();

            return response()->json([
                'success' => true,
                'message' => "Users retrieved successfull",
                'data' => $users,
            ]);
        } catch (\Exception $exception) {
            Log::info('Error getting users' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting users"

                ],
                500
            );
        }
    }

    public function getUserById($id)
    {
        try {

            Log::info('Getting user by id');
            $user = User::query()->find($id);

            if(!$user){
                return response()->json([
                    'success' => true,
                    'message' => "user not found",
                ],
                404
            );
            }
            
            return response()->json([
                'success' => true,
                'message' => "User retrieved successfull",
                'data' => $user,
            ]);
        } catch (\Exception $exception) {
            Log::info('Error getting user' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting user"

                ],
                500
            );
        }
    }

    public function joinChannelById($id)
    {
        try {
            $user = User::query()->find(auth()->user()->id);
            $user->channels()->attach(self::$id);

            return response()->json([
                'success' => true,
                'message' => "User joined to the party successfull",
                'data' => $user,
            ]);
        } catch (\Exception $exception) {
            Log::info($exception);
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot join the party'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getOutOfAChannelById($id)
    {
        try {
            $user = User::query()->find(auth()->user()->id);
            $user->channels()->detach(self::$id);

            return response()->json([
                'success' => true,
                'message' => "User lefted the party succesfully",
                'data' => $user,
            ]);
        } catch (\Exception $exception) {
            Log::info($exception);
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user couldnt left the party'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
