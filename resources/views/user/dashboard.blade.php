@extends('layout')
@section('home', 'active')
@section('slot')
    <main class="main">
        <section class="pt-30 pb-150 col-md-12">
            <div class="container">
                <div class="col-md-12 text-center">
                    <h1>Xush Kelibsiz! Bu Sizning Profilingiz</h1>
                    <p>Ma'lumotlarni o'zgartirish uchun, Rasm yoki Ism ustiga ikki marta bosing:)</p>
                </div>
                <br><br><br><br><br>
                <div class="row justify-content-center">
                    <div class="col-lg-12 d-flex justify-content-between">
                        <div class="col-12 d-flex">
                            @if($profile->photo)
                            <img id="photo" class="d-inline mr-15" style="border-radius: 2px!important;height: 100px!important;width: 100px!important;" src="{{ asset('uploads/' . $profile->photo) }}" alt="">
                            @else
                            <img id="photo" class="d-inline mr-15" style="border-radius: 2px!important;height: 100px!important;width: 100px!important;" src="{{ asset('assets/imgs/user.png') }}" alt="">
                            @endif
                            <div>
                                <h1 id="name">{{ $profile->name }}</h1>
                                <p>Emailingiz: {{ $profile->email }}</p>
                                <p>Ro'yxatdan o'tgan sanangiz: {{ \Carbon\Carbon::parse($profile->created_at)->format('Y-d-M H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br>
                    <div class="col-md-12">
                        <h1>Parolni yangilash</h1>
                        <form id="update-password-form" class="align-items-center">
                            <input class="my-1" type="password" id="current-password" placeholder="Joriy parolni kiriting" required>
                            <input class="my-1" type="password" id="new-password" placeholder="Yangi parolni kiriting" required>
                            <input class="my-1" type="password" id="confirm-password" placeholder="Yangi parolni tasdiqlang" required>
                            <button class="btn float-end" type="submit">Yangilash</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <div class="container">
        <h2>Diqqat!</h2>
        <p>Emailni o'zgartirib bilmaysiz. Agar emailni o'zgartirishni xohlasangiz adminga murojaat qiling. Bunda sizning hozirgi akkauntingizni o'chirishga to'g'ri keladi. Rasm formati 'jpg' yoki 'jpeg' yoki 'png' bo'lishi kerak!</p>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#name').on('dblclick', function() {
            var newName = prompt('Yangi ismingizni kiriting:');
            if (newName) {
                $.ajax({
                    url: '{{ route('user.update', $profile->id) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: newName,
                        _method: 'PUT'
                    },
                    success: function(data) {
                        $('#name').text(data.name);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });

        $('#photo').on('dblclick', function() {
            var newPhotoInput = $('<input/>').attr({
                type: 'file',
                accept: 'image/*'
            });

            newPhotoInput.on('change', function() {
                var formData = new FormData();
                formData.append('photo', this.files[0]);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'PUT');

                $.ajax({
                    url: '{{ route('user.update', $profile->id) }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.photo) {
                            $('#photo').attr('src', '{{ asset('uploads') }}/' + data.photo);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

            newPhotoInput.click();
        });

        $('#update-password-form').on('submit', function(e) {
            e.preventDefault();

            var currentPassword = $('#current-password').val();
            var newPassword = $('#new-password').val();
            var confirmPassword = $('#confirm-password').val();

            if (newPassword !== confirmPassword) {
                alert('Yangi parollar mos kelmaydi.');
                return;
            }

            $.ajax({
                url: '{{ route('user.update.password', $profile->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    current_password: currentPassword,
                    new_password: newPassword,
                    new_password_confirmation: confirmPassword,
                    _method: 'PUT'
                },
                success: function() {
                    alert('Parol muvaffaqiyatli yangilandi!');
                },
                error: function(xhr) {
                    alert('Xato yuz berdi: ' + xhr.responseJSON.message);
                }
            });
        });
    </script>
@endsection
