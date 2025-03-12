<?php

namespace App\Http\Controllers;

use App\Models\TeachersChat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeachersChatController extends Controller
{
    public function chat()
    {
        $messages = TeachersChat::query()
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get()
            ->reverse();
        $pin = TeachersChat::where('pin', '=', True)->get()->first();

        if ($pin === null) {
            return view('teacher.chat', [
                'messages' => $messages,
            ]);
        } else {
            return view('teacher.chat', [
                'messages' => $messages,
                'pin' => $pin,
            ]);
        }
    }

    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'message' => ['required', 'string', 'min:1'],
            'file' => 'mimes:audio/mp3,ppt,pptx,doc,docx,pdf,png,jpg,jpeg,webp,svg,xls,xlsx,mp3,mp4|max:10240',
        ]);

        // Fayl yuklash va saqlash
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('TeachersFile');
        }

        // Eski faylni o'chirish
        $this->manageFilesSize(Auth::id());

        // Yangi xabarni saqlash
        TeachersChat::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'file' => $filePath,
            'created_at' => Carbon::now('Asia/Tashkent'),
        ]);

        return redirect()->route('teachers.chat');
    }

    private function manageFilesSize($userId)
    {
        $maxSize = 20480000; // Maksimal ruxsat etilgan fayl hajmi (20MB)
        $files = TeachersChat::where('user_id', $userId)
            ->whereNotNull('file')
            ->get();

        $totalSize = 0;

        // Foydalanuvchi yuborgan fayllar hajmini hisoblash
        foreach ($files as $file) {
            $totalSize += Storage::size($file->file);
        }

        // Jami fayllar hajmi maksimal ruxsat etilgan hajmdan katta bo'lsa
        if ($totalSize > $maxSize) {
            // Eng birinchi yuborilgan faylni o'chirish
            $files = TeachersChat::where('user_id', $userId)
                ->whereNotNull('file')
                ->orderBy('created_at', 'asc')
                ->get();

            while ($totalSize > $maxSize && $files->isNotEmpty()) {
                $oldestFile = $files->shift();
                $totalSize -= Storage::size($oldestFile->file);
                Storage::delete($oldestFile->file);
                $oldestFile->file = null;
                $oldestFile->save();
            }
        }
    }


    public function pin($id, $i)
    {
        if (Auth::user()->hasRole('Admin')){
            $unpin = TeachersChat::where('pin','=',True)->get()->first();
            if ($unpin != null){
                $unpin->pin = false;
                $unpin->save();
            }
            if($i == 1) {
                $pin = TeachersChat::where('id', '=', $id)->get()->first();
                if ($pin === null) {
                    return redirect()->route('teachers.chat');
                } else {
                    $pin->pin = True;
                    $pin->save();
                    return redirect()->route('teachers.chat');
                }
            }
            return redirect()->route('teachers.chat');
        }else {
            return redirect()->route('teachers.chat');
        }
    }

    public function deleteMessage(Request $request)
    {
        $this->validate($request, [
            'message_id' => 'required|exists:chat_messages,id',
        ]);

        $message = TeachersChat::find($request->message_id);

        if (($message && $message->user_id === Auth::id() && $message->pin !== 1) || Auth::user()->hasRole('Admin')) {
            if ($message->file && Storage::exists($message->file)) {
                Storage::delete($message->file);
            }

            $message->delete();
        }
        return redirect()->route('teachers.chat');
    }
}
