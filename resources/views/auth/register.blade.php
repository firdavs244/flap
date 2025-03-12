@extends('layout')

@section('slot')
    <main class="main">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="{{ url('/') }}" rel="nofollow">Home</a>
                    <span></span> Register
                </div>
            </div>
        </div>
        <section class="pt-150 pb-150">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 m-auto">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="login_wrap widget-taber-content p-30 background-white border-radius-5">
                                    <div class="padding_eight_all bg-white">
                                        <div class="heading_s1">
                                            <h3 class="mb-30">Akkaunt Yaratish</h3>
                                        </div>
                                        <form method="POST" action="{{ route('register.complete.post') }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" value="{{ $email }}" id="email" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Ism</label>
                                                <input placeholder="Ismingiz" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" id="name" name="name" required>
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Parol</label>
                                                <input placeholder="Parol" type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                                @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="password_confirmation">Parolni kiring</label>
                                                <input placeholder="Parolni Tasdiqlang" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
                                                @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary">Yaratish</button>
                                        </form>
                                        <br>
                                        <div class="text-muted text-center">Akkauntingiz bormi? <a href="{{ route('login') }}">Kirish</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <img src="{{ asset('assets/imgs/login.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
