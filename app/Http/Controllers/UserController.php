<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $user = User::query()->find($id)->roles()->get();
            // OPCION CON FIRST
            // $user = User::query()->where('id', $id)->first();

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
}
