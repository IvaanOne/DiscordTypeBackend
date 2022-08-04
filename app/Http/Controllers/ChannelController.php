<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{
    public function getAllChannels()
    {
        try {
            Log::info('Getting all channels');
            $channels = DB::table('channels')->select('name')->get()->toArray();

            return response()->json([
                'success' => true,
                'message' => "Channels retrieved successfull",
                'data' => $channels,
            ]);
        } catch (\Exception $exception) {
            Log::info('Error getting channels' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting channels"

                ],
                500
            );
        }
    }

    public function getMessagesWithChannelById($id)
    {
        try {

            Log::info('Getting messages from channel by id');
            $channel = Channel::query()->find($id);
            $messages = Message::query()->get()->where('channel_id', '=', $id);
            // $messagesChannel = Channel::query()->find($id)->messages;


            if(!$channel){
                return response()->json([
                    'success' => true,
                    'message' => "Channel not found",
                ],
                404
            );
            }
            
            return response()->json([
                'success' => true,
                'message' => "Channel retrieved successfull",
                'data' => $channel, $messages
            ]);
        } catch (\Exception $exception) {
            Log::info('Error getting channel' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => ("Error getting the messages from the channel" . $exception->getMessage())

                ],
                500
            );
        }
    }

    public function createChannel(Request $request)
    {
        try {
            Log::info('Creating channel');
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'game_id' => 'required'
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
            $newChannel = Channel::create([
                'name' => $request->input('name'),
                'game_id' => $request->input('game_id'),
            ]);
            return response()->json([
                'success' => true,
                'message' => "Channel created successfull",
                'data' => $newChannel,
            ]);
        } catch (\Exception $exception) {
            Log::info('Error creating the channel' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error creating the channel"

                ],
                500
            );
        }
    }
    public function updateChannel($id, Request $request)
    {
        try {
            Log::info('Uptading channel');
            $channel = Channel::find($id);
            $validator = Validator::make($request->all(), [
                'name' => 'string',
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

            if ($request->input('name')) {
                $channel->name = $request->input('name');
            };

            $channel->save();
            return response()->json(
                [
                    'success' => true,
                    'message' => "Channel updated successfull",
                    'data' => $channel

                ],
                200
            );
        } catch (\Exception $exception) {
            Log::info('Error updating channel' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error updating channel"

                ],
                500
            );
        }
    }

    public function deleteChannelById($id)
    {
        try {
            Log::info('Deleting channel');
            $channel = Channel::query()->find($id);

            $channel->delete();
            return response()->json([
                'success' => true,
                'message' => "Channel deleted succesfull",
            ]);
        } catch (\Throwable $exception) {
            Log::info('Error deleting channel' . $exception->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => "Error deleting channel"

                ],
                500
            );
        }
    }
}
