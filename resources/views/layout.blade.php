<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <title>Surfside Media</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="">
    <meta property="og:type" content="">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/imgs/theme/favicon.ico')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html, body {
            overflow-x: hidden;
        }
        @media (max-width: 600px) {
            .container {
                width: 100%;
                overflow-x: hidden;
            }
        }
        /* Kichik ekranlar uchun yangilanish */
        @media (max-width: 768px) {
            .header-area {
                padding: 10px;
            }

            .header-middle {
                display: block;
            }

            .header-action-icon-2 img {
                height: 30px;
                width: 30px;
            }

            /*.main-menu {*/
            /*    display: block;*/
            /*}*/

            .mobile-promotion {
                display: block;
            }
        }
        .header-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-nav {
            display: flex;
            flex-direction: column;
        }
        .burger-icon {
            display: none;
        }

        @media (max-width: 768px) {
            .burger-icon {
                display: block;
            }
        }
        .footer-bottom {
            text-align: center;
        }

        @media (max-width: 768px) {
            .footer-mid {
                flex-direction: column;
            }

            .footer-list {
                text-align: center;
            }
        }

    </style>
    @stack('styles')
</head>
<body style="margin: 0!important;">
@auth
<header class="header-area header-style-1 header-height-2" style="background-color: whitesmoke!important;">
    <div class="header-middle header-middle-ptb-1 d-lg-block">
        <div class="container">
            <div class="header-wrap justify-content-between">
                <div class="logo logo-width-1">
                    <a href=""><img src="{{asset('assets/imgs/logo/logo.png')}}" alt="logo"></a>
                </div>
                    <div class="search-style-1 m-0 d-none">
                        <form action="#">
                            <input type="text" class="text-center" placeholder="{{ \Illuminate\Support\Facades\Auth::user()->name }}" disabled>
                        </form>
                    </div>
                    @auth
                        <div class="header-action-right mt-3">
                            <div class="header-action-2">
                                <div class="header-action-icon-2">
                                    <a href="{{ route('user.profile', \Illuminate\Support\Facades\Auth::id()) }}" style="width: 40px!important;height: 50px!important;" class="mini-cart-icon">
                                        @if(\Illuminate\Support\Facades\Auth::user()->photo)
                                        <img style="border-radius: 40px!important;height: 20px!important;width: 20px!important;scale: 200%!important;" src="{{ asset('uploads/' . \Illuminate\Support\Facades\Auth::user()->photo) }}" alt="">
                                        @else
                                        <img style="border-radius: 40px!important;height: 20px!important;width: 20px!important;scale: 200%!important;" src="{{ asset('assets/imgs/user.png') }}" alt="">
                                        @endif
                                        <span class="pro-count blue"><i class="fa-solid fa-user"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
            <div class="header-wrap header-space-between position-relative justify-content-center">
                <div class="header-nav d-lg-flex">
                    <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-lg-block">
                        <nav>
                            <ul>
                                @auth
                                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))
                                            <li><a class="@yield('admin','')" href="{{ route('admin.users.index') }}">Admin </a></li>
                                        @endif
                                            <li><a class="@yield('home','')" href="{{ route('user.dashboard') }}">Home</a></li>
                                @endauth
                                <li><a href="{{ route('chat') }}" class="@yield('chat','')">GlobalChat</a></li>
                                <li><a href="{{ route('stories.index') }}" class="@yield('quest','')">Questions</a></li>
                                    @if(\Illuminate\Support\Facades\Auth::user()->hasAnyRole(['Admin', 'Teacher']))
                                <li class="position-static"><a href="{{ route('teachers.chat') }}" class="@yield('teach','')">TeachersChat</a></li>
                                    @endif
                                <li><a href="{{ route('user', \Illuminate\Support\Facades\Auth::id()) }}">Messenger</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
</header>
@endauth

@yield('slot')

@auth
<footer class="main">
    <br><br><br>
    <section class="newsletter p-30 text-white wow fadeIn animated">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 d-flex justify-content-center">
                    <!-- Subscribe Form -->
                    <img class="icon-email" src="{{asset('assets/imgs/theme/icons/icon-email.svg')}}" alt="">
                    <form class="form-subcriber d-flex wow fadeIn animated col-md-9">
                        @php $mailto = 'ExampleEmail@gmail.com' @endphp
                        <input type="email" class="form-control bg-white font-small text-center" placeholder="{{ $mailto }}" disabled>
                        <a href="mailto:{{ $mailto }}" class="btn bg-dark text-white col-md-4">Xabar Jo'natish</a>
                    </form>
                    <!-- End Subscribe Form -->
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding footer-mid" style="background-color: whitesmoke!important;">
        <div class="container pt-15 pb-20">
            <div class="row  justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="widget-about font-md mb-md-5 mb-lg-0">
                        <div class="logo logo-width-1 wow fadeIn animated">
                            <a href="#"><img src="{{asset('assets/imgs/logo/logo.png')}}" alt="logo"></a>
                        </div>
                        <h5 class="mt-20 mb-10 fw-600 text-grey-4 wow fadeIn animated">Contact</h5>
                        <p class="wow fadeIn animated">
                            <strong>Address: </strong>Uzbekistan
                        </p>
                        <p class="wow fadeIn animated">
                            <strong>Phone: </strong>+998940870898
                        </p>
                        <p class="wow fadeIn animated">
                            <strong>Email: </strong>{{ $mailto }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 mob-center">
                    <h5 class="widget-title wow fadeIn animated">Install App</h5>
                    <div class="row">
                        <div class="col-md-8 col-lg-12">
                            <p class="wow fadeIn animated">From App Store or Google Play</p>
                            <div class="download-app wow fadeIn animated mob-app">
                                <a href="#" class="hover-up mb-sm-4 mb-lg-0"><img class="active" src="{{asset('assets/imgs/theme/app-store.jpg')}}" alt=""></a>
                                <a href="#" class="hover-up"><img src="{{asset('assets/imgs/theme/google-play.jpg')}}" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</footer>

{{--<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>--}}
<!-- Vendor JS-->
<script src="{{asset('assets/js/vendor/modernizr-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/jquery-migrate-3.3.0.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/slick.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery.syotimer.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/wow.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery-ui.js')}}"></script>
<script src="{{asset('assets/js/plugins/perfect-scrollbar.js')}}"></script>
<script src="{{asset('assets/js/plugins/magnific-popup.js')}}"></script>
<script src="{{asset('assets/js/plugins/select2.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/waypoints.js')}}"></script>
<script src="{{asset('assets/js/plugins/counterup.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery.countdown.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/images-loaded.js')}}"></script>
<script src="{{asset('assets/js/plugins/isotope.js')}}"></script>
<script src="{{asset('assets/js/plugins/scrollup.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery.vticker-min.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery.theia.sticky.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery.elevatezoom.js')}}"></script>
<!-- Template  JS -->
<script src="{{asset('assets/js/main.js?v=3.3')}}"></script>
<script src="{{asset('assets/js/shop.js?v=3.3')}}"></script>
<script>
    document.querySelector('.burger-icon').addEventListener('click', function() {
        document.querySelector('.header-nav').classList.toggle('active');
    });

</script>
@endauth
@stack('script')
</body>
</html>
