<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Group;
use App\Models\groupChat;
use App\Models\Question;
use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use function PHPUnit\Framework\isEmpty;

class QuestionsController extends Controller
{

    public function index($id)
    {
        $ban = Group::where('user_id', '=', Auth::id())->where('isCapitan', '=', null)->where('story_id','=',$id)->first();
        if($ban){
            return view('stories.banned');
        }else {
            $story = Story::where('id', '=', $id)->get()->first();
            $questions = Question::where('story_id', '=', $id)->get();
            $registered = Group::where('story_id', '=', $id)->orderByDesc('isCapitan')->paginate(15);
            $capitan = Group::where('story_id', '=', $id)->where('isCapitan','=',1)->get()->first();
            $count = Group::where('story_id', '=', $id)->get()->count();
            $messages = groupChat::query()
                ->orderBy('created_at', 'desc')
                ->take(100)
                ->get()
                ->reverse();
            $pin = groupChat::where('pin', '=', True)->get()->first();

            if ($pin === null) {
                return view('stories.questions.index', [
                    'questions' => $questions,
                    'story' => $story,
                    'registered' => $registered,
                    'messages' => $messages,
                    'capitan' => $capitan,
                    'count' => $count
                ]);
            } else {
                return view('stories.questions.index', [
                    'questions' => $questions,
                    'story' => $story,
                    'registered' => $registered,
                    'messages' => $messages,
                    'pin' => $pin,
                    'capitan' => $capitan,
                    'count' => $count
                ]);
            }
        }
    }

    public function capitan(Request $request, $id)
    {
        $this->validate($request, [
            'id' => 'required|exists:groups,id',
        ]);
        if ($request->rol == 0 || $request->rol == 1) {
            $g = Group::where('story_id', '=', $id)->where('isCapitan', '=', 1)->first();
            if ($g == null || $request->rol != 1) {
                $group = Group::where('id', '=', $request->id)->first();
                $uId = $group->user->id;
                if ($group) {
                    if ($request->rol === "0" || $request->rol === "1") {
                        $group->isCapitan = $request->rol;
                        $group->update();
                    }
                    else {
                        $questions = Question::where('story_id','=',$id)->get();
                        foreach ($questions as $question){
                            $answer = Answer::where('question_id','=',$question->id)
                                ->where('user_id','=',$uId)
                                ->get()->first();
                            if($answer) {
                                if ($answer->answer == null) {
                                    $answer->delete();
                                }
                            }
                        }
                        $group->isCapitan = $request->rol;
                        $group->update();
                    }
                }
            }
        }
        return redirect()->route('questions.index', $id);
    }

    public function sendMessage(Request $request, $id)
    {
        if (Group::where('user_id', '=', Auth::id())->where('story_id', '=', $id)->get()->first() || Auth::user()->hasAnyRole(['Admin', 'Teacher'])) {
            $this->validate($request, [
                'message' => ['required', 'string', 'min:1'],
                'file' => 'mimes:audio/mp3,ppt,pptx,doc,docx,pdf,png,jpg,jpeg,webp,svg,xls,xlsx,mp3,mp4|max:10240',
            ]);
            $group = Group::where('story_id', '=', $id)
                ->where('user_id', '=', Auth::id())
                ->where('isCapitan', '=', 1)
                ->first();
            if ($group || Auth::user()->hasAnyRole(['Admin', 'Teacher'])) {
                if ($request->hasFile('file')) {
                    $path = $request->file('file')->store('GroupFile');
                    $this->manageFilesSize(Auth::id());
                    groupChat::create([
                        'user_id' => Auth::id(),
                        'story_id' => $id,
                        'message' => $request->message,
                        'file' => $path,
                        'created_at' => Carbon::now('Asia/Tashkent'),
                    ]);
                } else {
                    groupChat::create([
                        'user_id' => Auth::id(),
                        'story_id' => $id,
                        'message' => $request->message,
                        'created_at' => Carbon::now('Asia/Tashkent'),
                    ]);
                }
            } else {
                groupChat::create([
                    'user_id' => Auth::id(),
                    'story_id' => $id,
                    'message' => $request->message,
                    'created_at' => Carbon::now('Asia/Tashkent'),
                ]);
            }
        }
        return redirect()->route('questions.index', $id);
    }

//    public function sendMessage(Request $request)
//    {
//        $this->validate($request, [
//            'message' => ['required', 'string', 'min:1'],
//            'file' => 'mimes:audio/mp3,ppt,pptx,doc,docx,pdf,png,jpg,jpeg,webp,svg,xls,xlsx,mp3,mp4|max:10240',
//        ]);
//
//        // Fayl yuklash va saqlash
//        $filePath = null;
//        if ($request->hasFile('file')) {
//            $filePath = $request->file('file')->store('TeachersFile');
//        }
//
//        // Eski faylni o'chirish
//        $this->manageFilesSize(Auth::id());
//
//        // Yangi xabarni saqlash
//        TeachersChat::create([
//            'user_id' => Auth::id(),
//            'message' => $request->message,
//            'file' => $filePath,
//            'created_at' => Carbon::now('Asia/Tashkent'),
//        ]);
//
//        return redirect()->route('teachers.chat');
//    }

    private function manageFilesSize($userId)
    {
        $maxSize = 20480000; // Maksimal ruxsat etilgan fayl hajmi (20MB)
        $files = groupChat::where('user_id', $userId)
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
            $files = groupChat::where('user_id', $userId)
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

    public function deleteMessage(Request $request, $id)
    {
        $this->validate($request, [
            'message_id' => 'required|exists:chat_messages,id',
        ]);

        $message = groupChat::find($request->message_id);

        if (($message && $message->user_id === Auth::id() && $message->pin !== 1) || Auth::user()->hasAnyRole(['Admin', 'Teacher'])) {
            if ($message->file && Storage::exists($message->file)) {
                Storage::delete($message->file);
            }

            $message->delete();
        }
        return redirect()->route('questions.index', $id);
    }

    public function pin($sId, $id, $i)
    {
        if (Auth::user()->hasRole('Admin')){
            $unpin = groupChat::where('pin','=',True)->get()->first();
            if ($unpin != null){
                $unpin->pin = false;
                $unpin->save();
            }
            if($i == 1) {
                $pin = groupChat::where('id', '=', $id)->get()->first();
                if ($pin === null) {
                    return redirect()->route('questions.index', $sId);
                } else {
                    $pin->pin = True;
                    $pin->save();
                    return redirect()->route('questions.index', $sId);
                }
            }
            return redirect()->route('questions.index', $sId);
        }else {
            return redirect()->route('questions.index', $sId);
        }
    }

    public function create($id)
    {
        $story = Story::where('id','=',$id)->get()->first();
        return view('stories.questions.create', compact('story'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'required|string|min:1',
            'photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('QuestionImages');
        }
        $question = Question::create([
            'story_id' => $request->id,
            'question' => $validatedData['question'],
            'photo' => $path ?? 'default.png',
            'created_at' => Carbon::now('Asia/Tashkent'),
            'status' => 'unsolved',
            'solved_at' => null,
        ]);

        return redirect()->route('questions.index', ['id' => $question->story_id])
            ->with('success', 'Question created successfully.');
    }

    public function group($id){
        Group::firstOrCreate([
            'story_id' => $id,
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('questions.index', $id);
    }

    public function left(Request $request, $id){
        Answer::where('user_id','=',$request->id)
            ->where('answer','=',null)
            ->delete();
        Group::where('story_id', '=', $id)
            ->where('user_id', '=', $request->id)
            ->delete();
        return redirect()->route('questions.index', $id);
    }

    public function show($id, $qId)
    {
        $ban = Group::where('user_id', '=', Auth::id())->where('isCapitan', '=', null)->where('story_id','=',$id)->first();
        if($ban){
            return view('stories.banned');
        }
        $story = Story::where('id','=',$id)->get()->first();
        $question = Question::where('id', '=', $qId)->get()->first();
        $answers = Answer::where('question_id', '=', $qId)->OrderByDesc('winner')->get();
        $inGroup = Group::where('story_id','=',$id)->get();
        $solved = Answer::where('winner', 1)
            ->where('question_id', $qId)
            ->get();
        foreach ($inGroup as $i){
            if ($i->user_id == Auth::id()){
                return view('stories.questions.show', [
                    'story' => $story,
                    'question' => $question,
                    'answers' => $answers,
                    'solved' => $solved->first()
                ]);
            }
        }
        $err = 'Savolni bajarish uchun guruhga qo\'shiling';
        return view('stories.questions.show', [
            'story' => $story,
            'question' => $question,
            'answers' => $answers,
            'err' => $err,
            'solved' => $solved->first()
        ]);
    }

    public function do($id, $qId){
        $ban = Group::where('user_id', '=', Auth::id())->where('isCapitan', '=', null)->where('story_id','=',$id)->first();
        if($ban){
            return view('stories.banned');
        }
        $inGroup = Group::where('story_id','=',$id)->get();
        if($inGroup->first() == null){
            return redirect()->route('questions.show', [$id, $qId]);
        }
        $l = 0;
        foreach ($inGroup as $i){
            if ($i->user_id == Auth::id()){
                $l = 1;
            }
        }
        if($l == 1) {
            $answers = Answer::where('winner', 1)
                ->where('question_id', $qId)
                ->get();
            if($answers->first() == null) {
                Answer::firstOrCreate([
                    'answer' => null,
                    'question_id' => $qId,
                    'user_id' => Auth::id(),
                    'correct' => null
                ]);
            }
        }
        unset($l);
        return redirect()->route('questions.show', [$id, $qId]);
    }

    public function answer(Request $request, $id, $qId)
    {
        $ban = Group::where('user_id', '=', Auth::id())->where('isCapitan', '=', null)->where('story_id','=',$id)->first();
        if($ban){
            return view('stories.banned');
        }
        $inGroup = Group::where('story_id','=',$id)->get();
        if($inGroup->first() == null){
            return redirect()->route('questions.show', [$id, $qId]);
        }
        $l = 0;
        foreach ($inGroup as $i){
            if ($i->user_id == Auth::id()){
                $l = 1;
            }
        }
        if($l == 1) {
            $ans = Answer::where('user_id', '=', Auth::id())
                ->where('question_id', '=', $qId)
                ->first();
            if ($ans) {
//                dd($request->answer);
                $ans->answer = $request->answer;
                $ans->created_at = Carbon::now('Asia/Tashkent');
                $ans->update();
            }
        }
        unset($l);

        return redirect()->route('questions.show', [$id, $qId]);
    }

    public function correct($id, $qId, $aId){
        $answers = Answer::where('winner', 1)
            ->where('question_id', $qId)
            ->get();
        if($answers->first() == null) {
            $ans = Answer::where('id', '=', $aId)->first();
            $ans->correct = true;
            $ans->updated_at = Carbon::now('Asia/Tashkent');
            $ans->update();
        }
        return redirect()->route('questions.show', [$id, $qId]);
    }

    public function incorrect($id, $qId, $aId){
        $answers = Answer::where('winner', 1)
            ->where('question_id', $qId)
            ->get();
        if($answers->first() == null) {
            $ans = Answer::where('id', '=', $aId)->first();
            if ($ans->winner != 1) {
                $ans->correct = false;
                $ans->updated_at = Carbon::now('Asia/Tashkent');
                $ans->update();
            }
        }
        return redirect()->route('questions.show', [$id, $qId]);
    }

    public function ansdelete(Request $aId, $id, $qId)
    {
        $ban = Group::where('user_id', '=', Auth::id())->where('isCapitan', '=', null)->where('story_id','=',$id)->first();
        if($ban){
            return view('stories.banned');
        }
        $a = Answer::where('id', '=', $aId->answer_id)->get();
        if (Auth::user()->hasAnyRole(['Admin', 'Teacher']) || $a->first()->correct === null){
            $a->first()->delete();
            return redirect()->route('questions.show', [$id, $qId]);
        }
    }

    public function winner($id, $qId, $aId){
        $answers = Answer::where('winner', 1)
            ->where('question_id', $qId)
            ->get();
        if($answers->first() == null) {
            $ans = Answer::where('id', '=', $aId)->first();
            $ans->correct = true;
            $ans->winner = true;
            $ans->updated_at = Carbon::now('Asia/Tashkent');
            $ans->update();
        }
        return redirect()->route('questions.show', [$id, $qId]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($sId, $qId)
    {
        $question = Question::find($qId);

        if ($question) {
            $imagePath = $question->photo;
            if ($imagePath && Storage::exists($imagePath)) {
                Storage::delete($imagePath);
            }

            $question->delete();
        }
        return redirect()->route('questions.index', $sId);
    }
}
