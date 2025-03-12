<?php

namespace App\Http\Controllers;

use App\Models\groupChat;
use App\Models\Question;
use App\Models\Story;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoriesController extends Controller
{

    public function index()
    {
        $stories = Story::paginate(12);
        return view('stories.index', compact('stories'));
    }


    public function create()
    {
        return view('stories.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('StoryImages');
        }
        $story = Story::create([
            'title' => $request->title,
            'body' => $request->body,
            'photo' => $path ?? 'default.png',
            'user_id' => Auth::id(),
            'created_at' => Carbon::now('Asia/Tashkent')
        ]);

        return redirect()->route('stories.index');
    }


    public function edit(Story $story)
    {
        return view('stories.edit', ['story' => $story]);
    }

    public function update(Request $request, Story $story)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('StoryImages');
            $validatedData['photo'] = $path;

            if ($story->photo !== 'default.png' && Storage::exists($story->photo)) {
                Storage::delete($story->photo);
            }
        }

        $story->update($validatedData);
        return redirect()->route('stories.index')->with('success', 'Story updated successfully.');
    }

    public function destroy(Story $story)
    {
        if ($story->photo !== 'default.png' && Storage::exists($story->photo)) {
            Storage::delete($story->photo);
        }

        $files = groupChat::where('story_id','=',$story->id)->whereNotNull('file')->get();
        foreach($files as $file){
            if($file && Storage::exists($file->file)) {
                Storage::delete($file->file);
            }
        }

        $images = Question::where('story_id','=',$story->id)->where('photo','!=','default.png')->get();
        foreach($images as $image){
            if($image && Storage::exists($image->photo)) {
                Storage::delete($image->photo);
            }
        }

        $story->delete();
        return redirect()->route('stories.index')->with('success', 'Story deleted successfully.');
    }
}
