<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $messages = ChatMessage::query()
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get()
            ->reverse();
        $pin = ChatMessage::where('pin','=',True)->get()->first();
        if ($pin === null){
            return view('chat', compact('messages'));
        } else {
            return view('chat', compact('messages', 'pin'));
        }
    }
//    public function getMessages()
//    {
//        $messages = ChatMessage::query()
//            ->orderBy('id','desc')
//            ->take(30)
//            ->get()->first();
//        $messages->load('users');
//        $pin = ChatMessage::where('pin','=',True)->get()->first();
//
//        if ($pin === null){
//            return response()->json([
//                'status' => 'success',
//                'messages' => $messages,
//            ]);
//        } else {
//            return response()->json([
//                'status' => 'success',
//                'messages' => $messages,
//                'pin' => $pin,
//            ]);
//        }
//    }



    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $message = new ChatMessage();
        $message->user_id = Auth::id();
        $message->message = $request->message;
        $message->created_at = Carbon::now('Asia/Tashkent');
        $message->save();
        $message->message = nl2br(e($message->message));
        $message->created_at = Carbon::parse('Asia/Tashkent')->format('Y d-M H:i:s');
        $message->is_admin = $message->user->hasRole('Admin');
        $message->load('user');

        return response()->json([
            'status' => true,
            'data' => $message,
        ]);
    }


    public function deleteMessage(Request $request)
    {
        $this->validate($request, [
            'message_id' => 'required|exists:chat_messages,id',
        ]);

        $message = ChatMessage::find($request->message_id);

        if (($message && $message->user_id === Auth::id() && $message->pin !== 1) || Auth::user()->hasRole('Admin')) {
            $message->delete();
            return redirect()->route('chat');
        } else {
            return redirect()->route('chat');
        }
    }

    public function pin($id, $i)
    {
        if (Auth::user()->hasRole('Admin')){
            $unpin = ChatMessage::where('pin','=',True)->get()->first();
            if ($unpin != null){
                $unpin->pin = false;
                $unpin->save();
            }
            if($i == 1) {
                $pin = ChatMessage::where('id', '=', $id)->get()->first();
                if ($pin === null) {
                    return redirect()->route('chat');
                } else {
                    $pin->pin = True;
                    $pin->save();
                    return redirect()->route('chat');
                }
            }
            return redirect()->route('chat');
        }else {
            return redirect()->route('chat');
        }
    }
}
