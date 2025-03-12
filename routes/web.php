<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\StoriesController;
use App\Http\Controllers\TeachersChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::middleware('guest')->group(function () {
    Route::get('auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::post('register/complete', [AuthController::class, 'completeRegistration'])->name('register.complete.post');
    Route::get('/register', [AuthController::class, 'register'])->name('register');

    Route::post('/register/submit', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/login', function () {
        \Illuminate\Support\Facades\Session::forget('user_email');
        return view('auth.login');
    })->name('login');

    Route::post('/login/submit', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:10,3');
});


Route::get('/banned', function (){
    if (Auth::user()->hasRole('Banned')) {
        return view('auth.blocked');
    } else{
        return redirect('/');
    }
})->middleware('auth')->name('banned');

Route::middleware('auth')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth','check.banned'])->group(function (){
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::put('/user/dashboard/{id}', [UserController::class, 'update'])->name('user.update');
    Route::put('/user/dashboard/{id}/update-password', [UserController::class, 'updatePassword'])->name('user.update.password');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat/messages', [ChatController::class, 'getMessages'])->name('chat.get-messages');
    Route::post('/chat/send-message', [ChatController::class,'sendMessage'])->name('chat.send-message');
    Route::delete('/chat/delete-message', [ChatController::class, 'deleteMessage'])->name('chat.delete-message');
    Route::get('group', [StoriesController::class, 'index'])->name('stories.index');
    Route::get('group/{id}/questions', [QuestionsController::class, 'index'])->name('questions.index');
    Route::post('group/{id}/sendMessage', [QuestionsController::class, 'sendMessage'])->name('questions.index.sendMessage');
    Route::delete('group/{id}/deleteMessage', [QuestionsController::class, 'deleteMessage'])->name('questions.index.deleteMessage');
    Route::get('group/{sId}/pin/{id}/{i}', [QuestionsController::class, 'pin'])->name('questions.pin');
    Route::post('group/{id}/questions/add', [QuestionsController::class, 'group'])->name('questions.group');
    Route::get('group/{id}/questions/{question}/show', [QuestionsController::class,'show'])->name('questions.show');
    Route::post('group/{id}/questions/{question}/show/do', [QuestionsController::class,'do'])->name('questions.show.do');
    Route::post('group/{id}/questions/{question}/answer', [QuestionsController::class,'answer'])->name('questions.show.answer');
    Route::delete(  'group/{id}/questions/{question}/answer/delete', [QuestionsController::class,'ansdelete'])->name('questions.show.answer.delete');
    Route::middleware('role:Admin,Teacher')->group(function (){
        Route::get('teachers/chat', [TeachersChatController::class, 'chat'])->name('teachers.chat');
        Route::post('teachers/chat/send-message', 'App\Http\Controllers\TeachersChatController@sendMessage')->name('teachers.chat.send-message');
        Route::delete('teachers/chat/delete', [TeachersChatController::class, 'deleteMessage'])->name('teachers.chat.delete');
        Route::get('group/create', [StoriesController::class, 'create'])->name('stories.create');
        Route::post('group/store', [StoriesController::class,'store'])->name('stories.store');
        Route::delete('group/{id}/questions/left', [QuestionsController::class, 'left'])->name('questions.group.left');
        Route::get('group/{story}/edit', [StoriesController::class,'edit'])->name('stories.edit');
        Route::put('group/{story}', [StoriesController::class,'update'])->name('stories.update');
        Route::delete('group/{story}', [StoriesController::class,'destroy'])->name('stories.destroy');
        Route::put('group/{id}/capitan', [QuestionsController::class, 'capitan'])->name('questions.capitan');
        Route::get('group/{id}/questions/create', [QuestionsController::class, 'create'])->name('questions.create');
        Route::post('group/{id}/questions/store', [QuestionsController::class,'store'])->name('questions.store');
        Route::get('group/{id}/questions/{question}/edit', [QuestionsController::class,'edit'])->name('questions.edit');
        Route::delete('group/{id}/questions/{question}', [QuestionsController::class,'destroy'])->name('questions.destroy');
        Route::get('group/{id}/questions/{question}/correct/{answer}', [QuestionsController::class,'correct'])->name('questions.answer.show.correct');
        Route::get('group/{id}/questions/{question}/winner/{answer}', [QuestionsController::class,'winner'])->name('questions.answer.show.winner');
        Route::get('group/{id}/questions/{question}/incorrect/{answer}', [QuestionsController::class,'incorrect'])->name('questions.answer.show.incorrect');
    });
});

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::put('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role.update');
        Route::get('users/search', [AdminController::class, 'search'])->name('users.search');
        Route::resource('users', AdminController::class)->only(['index', 'destroy']);
    });
    Route::get('/chat/{id}/pin/{i}', [ChatController::class, 'pin'])->name('chat.pin');
    Route::get('teachers/chat/{id}/pin/{i}', [TeachersChatController::class, 'pin'])->name('teachers.chat.pin');
});
