@extends('layout')
@section('slot')
    <div class="container">
    <div class="card text-center">
        <div class="card-header">
            @if($profile->roles->isNotEmpty())
                {{ $profile->roles->first()->name }}
            @else
                user
            @endif
            <br />
        @if($profile->photo)
            <a href="{{asset('uploads/' . $profile->photo)}}"><img style="border-radius: 30px!important; width: 180px!important;height: 180px!important;" alt="Media" src="{{asset('uploads/' . \Illuminate\Support\Facades\Auth::user()->photo)}}"></a>
        @else
            <a href="{{asset('assets/imgs/user.png')}}"><img style="border-radius: 30px!important; width: 180px!important;height: 180px!important;" alt="Media" src="{{asset('assets/imgs/user.png')}}"></a>
        @endif
        </div>
        <div class="card-body">
            <h2 @if($profile->hasRole('Admin')) id="cente" @endif class="card-title">{{ $profile->name }}</h2>
            <p style="line-height:30px ;font-size: 25px; color: #146c43">{{ $correctAnswers ? : '0' }} - marta g'olib bo'lgan</p>
            <p style="line-height:30px ;font-size: 25px" class="text-success">{{ $ans ? : '0' }} - ta to'g'ri javob bergan</p>
            <p style="line-height:30px ;font-size: 25px" class="text-danger">{{ $incAns ? : '0' }} - ta noto'g'ri javob bergan</p>
            <p style="line-height:30px ;font-size: 25px" class="text-warning">{{ $solving ? : '0' }} - ta savolni bajarmoqda</p>
            <br /><br />
            <a style="font-size: 20px" href="{{ route('user', $profile->id)}}"><i class="fa-regular fa-comments"></i>Xabar Yuborish</a>
            <br /><br />
            @if(\Illuminate\Support\Facades\Auth::id() === $profile->id)
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="shopping-cart-footer shopping-cart-button outline" style="background-color: transparent !important;color: #ca3c3c !important;border: none !important; font-size: 25px" href="{{ route('logout') }}" onclick="return confirm('Chiqishni Tasdiqlang')">Akkauntdan Chiqish</button>
                </form>
            @endif
        </div>
    </div>
@endsection
@push('styles')
            <style>
                #cente {
                    font-weight: bold;
                    font-size: 50px;
                    color: rgba(255,0,0,0.1);
                    /*text-transform: uppercase;*/
                    background-size: cover;
                    background-image: url({{ asset('assets/imgs/back1.jpeg') }});
                    -webkit-background-clip: text;
                    animation: background-text-animation 5s linear infinite;
                }
                #cente1 {
                    font-weight: bold;
                    font-size: 50px;
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
