@extends('layout')
@section('slot')
    <main class="main">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="index.html" rel="nofollow">Home</a>
                    <span></span> Login
                </div>
            </div>
        </div>
        <section class="pt-150 pb-150">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 m-auto">
                        <div class="row">
                            <div class="col-lg-5">
                                <div
                                    class="login_wrap widget-taber-content p-30 background-white border-radius-10 mb-md-5 mb-lg-0 mb-sm-5">
                                    <div class="padding_eight_all bg-white">
                                        <div class="heading_s1">
                                            <h3 class="mb-30">Login</h3>
                                        </div>
                                        @error('message')
                                        <div class="alert alert-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                        <form method="post" action="{{ route('login.submit') }}">
                                            @csrf
                                            <div class="form-group">
                                                <input type="text" required="" name="email" placeholder="Your Email"
                                                       value="{{ old('email') }}">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <input required="" type="password" name="password"
                                                       placeholder="Password">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="login_footer form-group">
{{--                                                <div class="chek-form">--}}
{{--                                                    <div class="custome-checkbox">--}}
{{--                                                        <input class="form-check-input" type="checkbox" name="checkbox"--}}
{{--                                                               id="exampleCheckbox1" value="">--}}
{{--                                                        <label class="form-check-label" for="exampleCheckbox1"><span>Remember me</span></label>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
                                            </div>
{{--                                            <div class="form-group">--}}
                                                <button type="submit" class="btn mb-2 btn-fill-out btn-block hover-up float-end"
                                                        name="login">Log in
                                                </button>
{{--                                                <a class="" href="{{ route('register') }}">Akkauntingiz yo'qmi?</a>--}}
{{--                                            </div>--}}
                                        </form>
                                        <p style="margin-top: 30px">
                                                Ro'yxat o'tish: <a href="{{ route('google.redirect') }}"> <i style="scale:110%!important;" class="fa-brands fa-google"></i></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1"></div>
                            <div class="col-lg-6">
                                <img src="assets/imgs/login.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
