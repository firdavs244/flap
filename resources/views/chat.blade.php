@extends('layout')
@section('chat', 'active')
@section('slot')
    <div class="container">
        <h1>Global Chat</h1>
        <section>
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-7 col-xl-8">
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
                                                @if(Auth::user()->hasRole('Admin'))
                                                    <a href="{{ route('chat.pin', [$pin->id, 0]) }}"><i style="rotate: 40deg!important; color: #146c43" class="fa-solid fa-thumbtack-slash"></i></a>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-muted small mb-0 d-inline">
                                                {{ \Carbon\Carbon::parse($pin->created_at)->format('Y d-M H:i:s') }}
                                            </p>
                                            @if (Auth::user()->hasRole('Admin'))
                                                <form style="display: inline" action="{{ route('chat.delete-message') }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirishni xoxlaysizmi?')">
                                                    @method('DELETE')
                                                    @csrf
                                                    <input type="hidden" name="message_id" value="{{ $pin->id }}">
                                                    <button class="text-danger" style="border: 0;background-color: unset;padding: 0;" type="submit"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="cente1" class="card-body">
                                        <p class="mb-0">
                                            {!! nl2br(e($pin->message)) !!}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endisset
                        <hr style="height: 5px">
                        <br>
                        <ul id="list-message" class="list-unstyled m-2 p-2" style="height: 800px; overflow-y: scroll;">
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
                                                <p href="{{ route('user.profile', $message->user->id) }}" style="width: 75%!important;" class="fw-bold mb-0">
                                                    {{ $message->user->name }}
                                                    @if(Auth::user()->hasRole('Admin'))
                                                        @if($message->pin == 1)
                                                            <a href="{{ route('chat.pin', [$pin->id, 0]) }}"><i style="rotate: 40deg!important; color: #146c43" class="fa-solid fa-thumbtack-slash"></i></a>
                                                        @else
                                                            <a href="{{ route('chat.pin', [$message->id, 1]) }}"><i style="rotate: 40deg!important; color: #146c43" class="fa-solid fa-thumbtack"></i></a>
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-muted small mb-0 d-inline">
                                                    {{ \Carbon\Carbon::parse($message->created_at)->format('Y d-M H:i:s') }}
                                                </p>
                                                @if ((Auth::id() === $message->user_id && $message->pin !== 1) || Auth::user()->hasRole('Admin'))
                                                    <form style="display: inline" action="{{ route('chat.delete-message') }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirishni xoxlaysizmi?')">
                                                        @method('DELETE')
                                                        @csrf
                                                        <input type="hidden" name="message_id" value="{{ $message->id }}">
                                                        <button class="text-danger" style="border: 0;background-color: unset;padding: 0;" type="submit"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                        <div id="center" class="card-body">
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
                        <form id="send-message-form">
                            <div class="form-group">
                                <div data-mdb-input-init class="form-outline">
                                    <textarea type="text" id="message-input" class="form-control @error('message') is-invalid @enderror"
                                              placeholder="Xabaringizni bu yerda yozing..." name="message" rows="8">{{ old('message') }}</textarea>
                                    @error('message')
                                    <label class="form-label invalid-feedback" for="textAreaExample2">{{ $message }}</label>
                                    @enderror
                                </div>
                                <br>
                                <button id="btn_send_message" type="submit" class="btn btn-info btn-rounded float-end">Yuborish</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        $(document).ready(function() {

            $('#btn_send_message').on('click', function(event) {
                event.preventDefault();

                let message = $('#message-input').val().trim();

                if (message === '') {
                    return;
                }

                $.ajax({
                    url: '{{ route('chat.send-message') }}',
                    method: 'POST',
                    data: {
                        message: message,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if(data.status == true){
                            let user_photo ='';
                            let chat_pin ='';
                            let messageId = data.data.id;

                            let auth_user = '{{\Illuminate\Support\Facades\Auth::id()}}';
                            let auth_user_role = '{{Auth::user()->hasRole('Admin')}}';
                            let formattedDate = data.data.created_at;

                            console.log('Formatted Date:', formattedDate);

                            if(data.data.user.photo != null){
                                user_photo = `<img src="{{ asset("uploads/") }}/${data.data.user.photo}" alt="avatar" class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" style="width: 60px!important;height: 60px!important;">`
                            }else{
                                user_photo = `<img src="{{ asset('assets/imgs/user.png') }}" alt="avatar" class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" style="width: 60px!important;height: 60px!important;">`
                            }

                            var routeUrl = "{{ route('chat.pin', [':i', 1]) }}"
                            varouteUrl = routeUrl.replace(':i', messageId);
                            chat_pin = `<a href="${varouteUrl}"><i style="rotate: 40deg!important; color: #146c43" class="fa-solid fa-thumbtack"></i></a>`;

                            // Show or hide the form based on pin status and user role
                            if ((auth_user == data.data.user.id) || auth_user_role) {
                                form = `<form style="display: inline" action="{{ route('chat.delete-message') }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirishni xoxlaysizmi?')">
                                                @method('DELETE')
                                @csrf
                                <input type="hidden" name="message_id" value="${data.data.id}">
                                                <button class="text-danger" style="border: 0;background-color: unset;padding: 0;" type="submit"><i class="fa-solid fa-trash"></i></button>
                                            </form>`
                            }

                            let is_admin = data.data.is_admin;
                            if(is_admin){
                                Admin = 'id="cente"';
                            }else{
                                Admin = '';
                            }

                            $("#list-message").append(`
                                <li class="d-flex justify-content-center mb-4">
                                    ${user_photo}
                                    <div style="width: 764px !important;" class="card">
                                        <div class="card-header d-flex justify-content-between p-3" style="background-color: whitesmoke">
                                            <div ${Admin} style="width: 75%!important;" class="d-flex align-items-center">
                                                <p style="width: 75%!important;" class="fw-bold mb-0">
                                                    ${ data.data.user.name }
                                                    @if(Auth::user()->hasRole('Admin'))
                                                        ${chat_pin}
                                                    @endif
                                                </p>
                                            </div>
                                        <div>
                                        <p class="text-muted small mb-0 d-inline">
                                            ${formattedDate}
                                        </p>
                                            <form style="display: inline" action="{{ route('chat.delete-message') }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirishni xoxlaysizmi?')">
                                                @method('DELETE')
                                                @csrf
                                                <input type="hidden" name="message_id" value="${data.data.id}">
                                                <button class="text-danger" style="border: 0;background-color: unset;padding: 0;" type="submit"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div id="center" class="card-body">
                                        <p class="mb-0">
                                            ${data.data.message}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        `);
                    }
                    $("#message-input").val('')
                    const messageList = document.getElementById('list-message');
                    messageList.scrollTop = messageList.scrollHeight;
                    },
                    error:function (e){
                        console.log(e);
                    }
                });
            });
        });
        const messageList = document.getElementById('list-message');
        messageList.scrollTop = messageList.scrollHeight;
    </script>
@endpush
