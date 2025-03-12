<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed
            $user = Auth::user();
            if ($user->hasRole('Admin')) {
                Session::forget('failed_login_attempts');
                return redirect()->route('admin.dashboard');
            } else {
                Session::forget('failed_login_attempts');
                return redirect()->route('user.dashboard');
            }
        } else {
            return redirect()->back()->withErrors(['message' => 'Invalid email or password.']);
        }
    }

    // Google'ga redirect qilish
    public function redirectToGoogle(Request $request)
    {
        return Socialite::driver('google')->redirect();
    }

    // Google'dan qaytish va foydalanuvchini tizimga kiritish yoki ro'yxatdan o'tkazish
    public function handleGoogleCallback(Request $request)
    {
        $user = Socialite::driver('google')->stateless()->user();
        $email = $user->getEmail();
        $user = User::where('email', $email)->first();
        if ($user) {
            // Agar foydalanuvchi mavjud bo'lsa, tizimga kirish
            Auth::login($user);
            return redirect()->intended('/user/dashboard');
        } else {
            Session::put('user_email', $email);
            return redirect()->route('register');
        }
    }

    public function register(Request $request)
    {
        $email = Session::get('user_email');

        if($email){
            return view('auth.register', compact('email'));
        } else {
            return redirect()->route('login');
        }
    }

    // To'liq ro'yxatdan o'tish - ism, parol va parolni tasdiqlash
    public function completeRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);

        $email = Session::get('user_email');

        if (!$email) {
            return redirect()->route('login');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->intended('/user/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Reset failed login and registration attempts count
//        Session::forget('failed_login_attempts');
//        Session::forget('failed_registration_attempts');

        return redirect('/');
    }
}
