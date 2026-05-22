<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index(User $user)
    {
        $messages = Message::with('user')->oldest()->get();

        return view('chat', compact('messages', 'user'));
    }

    public function send(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $message = Message::create([
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message, $user->id))->toOthers();

        return response()->json([
            'success' => true
        ]);
    }
}