@extends('layout')
@section('quest', 'active')
@section('slot')
    <section class="mt-50 mb-50">
        <div class="container custom">
            <div class="row">
                <div class="col-lg-12">
                    <div class="single-header mb-50">
                        <div  class="d-flex justify-content-between align-items-center">
                            <h1 class="font-xxl text-brand">Savollar</h1>
                            @php $i = 0 @endphp
                            @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']))
                                <a class="btn btn-primary" href="{{ route('questions.create', $story->id) }}">
                                    Savol Qo'shish
                                </a>
                            @elseif(isset($registered->first()->story_id))
                                @foreach($registered as $regist)
                                    @if($regist->user_id == \Illuminate\Support\Facades\Auth::id())
{{--                                    <form action="{{ route('questions.group.left', $story->id) }}" method="post">--}}
{{--                                        @csrf--}}
{{--                                        <button class="btn btn-primary" type="submit">--}}
{{--                                            Guruhdan Chiqish--}}
{{--                                        </button>--}}
{{--                                    </form>--}}
                                        @php $i = 0xbc @endphp
                                        @break
                                    @endif
                                @endforeach
                                @if($i != 0xbc)
                                    <form action="{{ route('questions.group', $story->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-primary" type="submit">
                                            Guruhga Qo'shilish
                                        </button>
                                    </form>
                                @endif
                            @else
                                <form action="{{ route('questions.group', $story->id) }}" method="post">
                                    @csrf
                                    <button class="btn btn-primary" type="submit">
                                        Guruhga Qo'shilish
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="mt-5">
            <div class="card-header">
                <h1 class="text-center" id="post-title">{{ $story->title }}</h1>
            </div>
            <div class="card-body">
                <p id="post-content" style="font-size: 24px">Guruh Haqida <br /> {!! nl2br(e($story->body)) !!}</p>
                @if($story->photo == 'default.png')
                @else
                    <a href="{{ asset('storage/' . $story->photo) }}">
                        <img style="height: 400px!important;" src="{{ asset('storage/' . $story->photo) }}" alt="">
                    </a>
                @endif
                <p class="mt-2">{{ $story->user->name }} tomonidan</p>
                <p class="mt-2">{{ \Carbon\Carbon::parse($story->created_at)->format('Y d-M H:i:s') }} da yaratildi</p>
            </div>
            <br>
        </div>
    </div>
    <div class="container">
        <hr style="height: 5px!important;">
        <div class="mt-5">
            <div class="card-header">
                <h1 id="post-title">Savollar</h1>
            </div>
        </div>
        @php
            $i = 0
        @endphp
        @foreach($questions as $question)
            @php
                $solved = \App\Models\Answer::where('winner', 1)
            ->where('question_id', $question->id)
            ->get()->first();
            @endphp
        <div class="card mb-3">
            <div class="row g-0 align-items-center @if($solved == null) @else alert-success @endif">
                <div class="col-md-2">
                    @if($question->photo == 'default.png')
                            <img src="{{ asset('assets/imgs/default.png') }}" class="img-fluid rounded-start" alt="...">
                    @else
                        <a href="{{ asset('storage/' . $question->photo) }}">
                        <img src="{{ asset('storage/' . $question->photo) }}" class="img-fluid rounded-start" alt="...">
                        </a>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">{{ ++$i }} - Savol</h5>
                        <p class="card-text">{!! nl2br(e($question->question)) !!}</p>
                        <p class="card-text"><small class="text-muted">{{ \Carbon\Carbon::parse($question->created_at)->format('Y d-M H:i:s') }}</small></p>
                    </div>
                </div>
{{--                <div class="col-md-1">--}}
{{--                    <a href="" class="btn btn-success mr-5" style="background-color: rgb(224, 168, 0)!important;color: black!important; border: none">Batafsil</a>--}}
{{--                </div>--}}
                <div class="col-md-2">
                    <a href="{{ route('questions.show',[$story->id, $question->id]) }}" class="btn btn-success" style="background-color: rgb(33, 136, 56)!important; border: none">Kirish</a>
                    @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']))
                    <form onsubmit="return confirm('Amalni tasdiqlang!')" style="margin-left: 20px!important;display: inline-block;" action="{{ route('questions.destroy', [$story->id, $question->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button style="background: none; border: none; cursor: pointer; color: #dc3545;" type="submit"><i style="scale: 200%" class="fa-regular fa-trash-alt"></i></button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <br><br>
    <br><br>
    <div class="container">
    <div class="mt-5">
        <div class="card-header">
            <h1 id="post-title">Guruh Uchun Chat</h1>
        </div>
    </div>
        <br>
        @isset($pin)
            <li class="d-flex justify-content-center mb-4">
                @if($pin->user->photo)
                    <img src="{{ asset('uploads/' . $pin->user->photo) }}" alt="avatar"
                         class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" style="width: 60px!important;height: 60px!important;cursor: pointer"
                         onclick="window.location.href='{{ route('user.profile', $pin->user->id) }}'">
                @else
                    <img src="{{ asset('assets/imgs/user.png') }}" alt="avatar"
                         class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" style="width: 60px!important;height: 60px!important;cursor: pointer"
                         onclick="window.location.href='{{ route('user.profile', $pin->user->id) }}'">
                @endif
                    <div style="width: 764px !important;" class="card">
                    <div class="card-header d-flex justify-content-between p-3" style="background-color: whitesmoke">
                        <div @if($pin->user->hasRole('Admin')) id="cente" @endif style="width: 75%!important;" class="d-flex align-items-center">
                            <p style="width: 75%!important;" class="fw-bold mb-0">
                                {{ $pin->user->name }}
                                @if(Auth::user()->hasAnyRole(['Admin','Teacher']))
                                    <a href="{{ route('questions.pin', [$story->id, $pin->id, 0]) }}"><i style="rotate: 40deg!important; color: #146c43" class="fa-solid fa-thumbtack-slash"></i></a>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-muted small mb-0 d-inline">
                                {{ \Carbon\Carbon::parse($pin->created_at)->format('Y d-M H:i:s') }}
                            </p>
                            @if (Auth::user()->hasAnyRole(['Admin','Teacher']))
                                <form style="display: inline" action="{{ route('questions.index.deleteMessage', $story->id) }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirishni xoxlaysizmi?')">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="message_id" value="{{ $pin->id }}">
                                    <button class="text-danger" style="border: 0;background-color: unset;padding: 0;" type="submit"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div id="cente1" class="card-body">
                        @if($pin->file && \Illuminate\Support\Facades\Storage::exists($pin->file))
                            <a href="{{ asset('storage/' . $pin->file) }}" style="scale: 150%!important;"><i style="scale: 150%!important;" class="fa-solid fa-paperclip"></i></a>
                        @endif
                        <p class="mb-0">
                            {!! nl2br(e($pin->message)) !!}
                        </p>
                    </div>
                </div>
            </li>
        @endisset
        <hr style="height: 5px">
        <br>
        <ul id="list-message" class="list-unstyled" style="height: 800px; overflow-y: scroll">
            @foreach ($messages as $message)
                <li class="d-flex justify-content-center mb-4">
                    @if($message->user->photo)
                        <img src="{{ asset('uploads/' . $message->user->photo) }}" alt="avatar"
                             class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" style="width: 60px!important;height: 60px!important;cursor: pointer!important;"
                             onclick="window.location.href='{{ route('user.profile', $message->user->id) }}'">
                    @else
                        <img src="{{ asset('assets/imgs/user.png') }}" alt="avatar"
                             class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" style="width: 60px!important;height: 60px!important;cursor: pointer!important;"
                             onclick="window.location.href='{{ route('user.profile', $message->user->id) }}'">
                    @endif
                        <div style="width: 764px !important;" class="card">
                        <div class="card-header d-flex justify-content-between p-3" style="background-color: whitesmoke">
                            <div @if($message->user->hasRole('Admin')) id="cente" @endif style="width: 75%!important;" class="d-flex align-items-center">
                                <p style="width: 75%!important;" class="fw-bold mb-0">
                                    {{ $message->user->name }}
                                    @if(Auth::user()->hasAnyRole(['Admin','Teacher']))
                                        @if($message->pin == 1)
                                            <a href="{{ route('questions.pin', [$story->id, $pin->id, 0]) }}"><i style="rotate: 40deg!important; color: #146c43" class="fa-solid fa-thumbtack-slash"></i></a>
                                        @else
                                            <a href="{{ route('questions.pin', [$story->id, $message->id, 1]) }}"><i style="rotate: 40deg!important; color: #146c43" class="fa-solid fa-thumbtack"></i></a>
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-muted small mb-0 d-inline">
                                    {{ \Carbon\Carbon::parse($message->created_at)->format('Y d-M H:i:s') }}
                                </p>
                                @if ((Auth::id() === $message->user_id && $message->pin !== 1) || Auth::user()->hasRole('Admin'))
                                    <form style="display: inline" action="{{ route('questions.index.deleteMessage', $story->id) }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirishni xoxlaysizmi?')">
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" name="message_id" value="{{ $message->id }}">
                                        <button class="text-danger" style="border: 0;background-color: unset;padding: 0;" type="submit"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div id="center" class="card-body">
                            @if($message->file && \Illuminate\Support\Facades\Storage::exists($message->file))
                                <a href="{{ asset('storage/' . $message->file) }}" style="scale: 150%!important;"><i style="scale: 150%!important;" class="fa-solid fa-paperclip"></i></a>
                            @endif
                            <p class="mb-0">
                                {!! nl2br(e($message->message)) !!}
                            </p>
                        </div>
                    </div>
                </li>
            @endforeach
            <span id="end"></span>
        </ul>
        <hr>
        @php
        foreach($registered as $regist){
            if($regist->user_id == \Illuminate\Support\Facades\Auth::id()){
                $active = $regist;
                break;
            }
        }
        @endphp
        @if(isset($active) || \Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin','Teacher']))
            <form id="send-message-form" action="{{ route('questions.index.sendMessage', $story->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div data-mdb-input-init class="form-outline">
                                        <textarea type="text" id="message-input" class="form-control @error('message') is-invalid @enderror"
                                                  placeholder="Xabaringizni bu yerda yozing..." name="message" rows="8">{{ old('message') }}</textarea>
                        @error('message')
                        <label class="form-label invalid-feedback" for="textAreaExample2">{{ $message }}</label>
                        @enderror
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-7">
                            @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']) || (isset($active) && $active->isCapitan == 1))
                            <input type="file" class="form-control border-0" name="file">
                            @endif
                        </div>
                        <div class="col-md-5">
                            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-info btn-rounded float-end" style="display:inline!important;">Yuborish</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    <br><br><br>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Role</th>
            @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']))
            <th>Action</th>
            @endif
        </tr>
        </thead>
        @php
            $latest = ceil($count/15);
            if(isset($_GET['page'])){
                $i = $_GET['page'] * 15 - 15;
            }else{
                $i = 0;
            }
        @endphp
        <tbody>
        @foreach ($registered as $regist)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $regist->user->name }}</td>
                @if($regist->isCapitan == 1)
                    <td>Capitan</td>
                @elseif($regist->isCapitan === 0)
                    <td>User</td>
                @else
                    <td>Banned</td>
                @endif
                @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']))
                <td class="d-flex align-items-center justify-content-between">
                    <form class="d-flex align-items-center col-md-6" action="{{ route('questions.capitan', $story->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <select id="rol" name="rol" style="height: 30px!important;" class="form-control text-center p-1 d-inline">
                            <option value="1">Capitan</option>
                            <option value="0" selected>User</option>
                            <option value="">Banned</option>
                        </select>
                        <input type="hidden" name="id" value="{{ $regist->id }}">
                        <button class="text-success d-inline" style="border: 0;background-color: white;padding: 0;" type="submit" onclick="
                            var dropdown = document.getElementById('rol');
                            var selectedValue = dropdown.value;
                            switch (selectedValue){
                                    case '1':
                                        return (confirm('{{ $regist->user->name }} -> Capitan?'));
                                    case '0':
                                        return (confirm('{{ $regist->user->name }} -> User?'));
                                    case '':
                                        return (confirm('{{ $regist->user->name }} -> Banned?'));
                                }
                                "><i class="fa-solid fa-check ml-5"></i></button>
                    </form>
                    <form class="d-flex align-items-center" action="{{ route('questions.group.left', $story->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="id" value="{{ $regist->user->id }}">
                        &nbsp;&nbsp;
                        <button class="text-danger d-inline" style="border: 0;background-color: white;padding: 0;" type="submit" onclick="return confirm('{{ $regist->user->name }} ni guruhdan chiqarishni xoxlaysizmi?')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
        {{ $registered->links() }}
    </div>
@endsection
@push('styles')
    <style>
        #cente p{
            font-weight: bold;
            font-size: 20px;
            color: rgba(255,0,0,0.1);
            /*text-transform: uppercase;*/
            background-size: cover;
            background-image: url({{ asset('assets/imgs/back1.jpeg') }});
            -webkit-background-clip: text;
            animation: background-text-animation 5s linear infinite;
        }
        #cente1 p{
            font-weight: bold;
            font-size: 20px;
            font-family: "Leelawadee UI Semilight", serif;
            color: rgba(255,0,0,0.1);
            /*text-transform: uppercase;*/
            background-size: cover;
            background-image: url({{ asset('assets/imgs/back.jpeg') }});
            -webkit-background-clip: text;
            animation: background-text-animation linear 50s infinite;
        }
        @keyframes background-text-animation {
            0%{
                background-position: left 0px top 50%;
            }
            50%{
                background-position: left 1500px top 50%;
            }
            100%{
                background-position: left 0px top 50%;
            }
        }

    </style>
@endpush
@push('script')
    <script>
        const messageList = document.getElementById('list-message');
        messageList.scrollTop = messageList.scrollHeight;
    </script>
@endpush
