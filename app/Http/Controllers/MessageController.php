<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    public function createMessage(Request $request)
    {
        try {
            Log::info('Creating message');
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'channel_id' => 'required|number'
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
            $user = User::find(auth()->user()->id);
            if (!$user->channels->contains($request->input('channel_id'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, you arent in this channel'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $newMessage = Message::create([
                'content' => $request->input('content'),
                'channel_id' => $request->input('channel_id'),
                'user_id' => auth()->user()->id
            ]);
            return response()->json([
                'success' => true,
                'message' => "Message created successfull",
                'data' => $newMessage,
            ]);
        } catch (\Exception $exception) {
            Log::info('Error creating the message' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error creating the message"

                ],
                500
            );
        }
    }
    public function updateMessageById($id, Request $request)
    {
        try {
            Log::info('Uptading message');
            $message = Message::find($id);
            $validator = Validator::make($request->all(), [
                'content' => 'string',
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

            if ($request->input('content')) {
                $message->content = $request->input('content');
            };

            $message->save();
            return response()->json(
                [
                    'success' => true,
                    'message' => "Message updated successfull",
                    'data' => $message

                ],
                200
            );
        } catch (\Exception $exception) {
            Log::info('Error updating message' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error updating message"

                ],
                500
            );
        }
    }

    public function deleteMessageById($id)
    {
        try {
            Log::info('Deleting message');
            $message = Message::query()->find($id);

            $message->delete();
            return response()->json([
                'success' => true,
                'message' => "Message deleted succesfull",
            ]);
        } catch (\Throwable $exception) {
            Log::info('Error deleting message' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error deleting message"

                ],
                500
            );
        }
    }
}
