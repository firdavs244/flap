@extends('layout')
@section('admin', 'active')
@section('slot')
    <main class="main">
        <section class="pt-150 pb-150">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Admin Dashboard</h1>
                        <p>Welcome to your dashboard!</p>
                        <h2>Ro'yxatdan o'tgan foydalanuvchilar</h2>
                        <br>
                        <div class="search-style-1 m-0 col-md-12">
                            <form action="#" class="col-md-12">
                                <input type="text" id="search" placeholder="Search registered users">
                            </form>
                        </div>
                        <br>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="user-list">
{{--                            @php--}}
{{--                                $latest = ceil($count/15);--}}
{{--                                if(isset($_GET['page'])){--}}
{{--                                    $i = $_GET['page'] * 15 - 15;--}}
{{--                                }else{--}}
{{--                                    $i = 0;--}}
{{--                                }--}}
{{--                            @endphp--}}

                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ ($users->currentpage()-1)*$users->perpage()+ ($loop->index + 1) }}</td>
                                    <td title="Bazadagi id - {{ $user->id }}">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->roles->isNotEmpty())
                                            {{ $user->roles->first()->name }}
                                        @else
                                            No role assigned
                                        @endif
                                    </td>
                                    <td class="d-flex align-items-center">
                                        <form class="d-inline" style="scale: 150%" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('{{ $user->name }}ni o\'chirishni xoxlaysizmi?')" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-danger" style="border: 0;background-color: white;padding: 0;" type="submit"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                        &nbsp; &nbsp;
                                        <form class="d-flex align-items-center" action="{{ route('admin.users.role.update', $user) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <select name="role_id" style="height: 30px!important;" class="form-control p-1 d-inline">
                                                @foreach (\App\Models\Role::all() as $role)
                                                    @if($role->name !== 'Admin')
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endif
                                                @endforeach
                                                <option value="0" selected>No role assigned</option>
                                            </select>
                                            &nbsp;&nbsp;
                                            <button onclick="return confirm('Amalni Tasdiqlang!')" class="text-success d-inline" style="border: 0;background-color: white;padding: 0;" type="submit"><i class="fa-solid fa-check"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div style="height: 34px!important;" class="d-flex align-items-center">
{{--                            <a style="height: 100%!important;" class="relative inline-flex items-center px-2 pt-1 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md" href="?page=1">1</a><div>{{ $users->links() }}</div><a style="height: 100%!important;" class="relative inline-flex items-center px-2 pt-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" href="{{ '?page=' . $latest }}">{{ $latest }}</a>--}}
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $('#search').on('keyup', function() {
            let query = $(this).val();
            $.ajax({
                url: "{{ route('admin.users.search') }}",
                type: "GET",
                data: {'query': query},
                success: function(data) {
                    $('#user-list').html(data);
                }
            })
        });
    </script>
@endsection
