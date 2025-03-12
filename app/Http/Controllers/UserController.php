<?php
namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $profile = User::where('id', Auth::id())->first();
        return view('user/dashboard', compact('profile'));
    }

    public function profile($id)
    {
        $profile = User::find($id);
        $correctAnswers = Answer::where('user_id','=',$id)
            ->where('winner','=',True)->count();
        $ans = Answer::where('user_id','=',$id)
            ->where('correct','=',True)->count();
        $incAns = Answer::where('user_id','=',$id)
            ->where('correct','=',False)->count();
        $solving = Answer::where('user_id','=',$id)
            ->where('correct','=',null)->count();
        return view('user/profile')->with([
            'correctAnswers' => $correctAnswers,
            'ans' => $ans,
            'incAns' => $incAns,
            'profile' => $profile,
            'solving' => $solving
            ]);
    }

    public function update(Request $request, $id)
    {
        $profile = User::find($id);

        if ($request->has('name')) {
            $this->validate($request, [
                'name' => 'required|min:3|max:30|string|regex:/^[A-Za-z]{3}[A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/|unique:users,name'
            ]);
            $profile->name = $request->input('name');
        }

        if ($request->hasFile('photo')) {
            $this->validate($request, [
                'photo' => 'nullable|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($profile->photo && File::exists(public_path('uploads/' . $profile->photo))) {
                File::delete(public_path('uploads/' . $profile->photo));
            }

            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $profile->photo = $filename;
        }

        $profile->save();

        return response()->json([
            'name' => $profile->name,
            'photo' => $profile->photo
        ]);
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        $profile = User::find($id);

        if (!Hash::check($request->input('current_password'), $profile->password)) {
            return response()->json(['message' => 'Joriy parol noto\'g\'ri.'], 400);
        }

        $profile->password = Hash::make($request->input('new_password'));
        $profile->save();

        return response()->json(['success' => true]);
    }
}
