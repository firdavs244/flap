<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Psy\bin;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Admin');
        })->paginate();

        return view('admin.dashboard')->with(['users' => $users]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $perPage = 15; // Har bir sahifada nechta foydalanuvchi ko'rsatiladi
        $page = $request->input('page', 1);

        $users = User::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
        })
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'Admin');
            })
            ->with('roles')
            ->paginate($perPage, ['*'], 'page', $page);

        $output = '';
        foreach ($users as $user) {
            $output .= '
            <tr>
                <td>' . $user->id . '</td>
                <td title="Bazadagi id - ' . $user->id . '">' . $user->name . '</td>
                <td>' . $user->email . '</td>
                <td>' . ($user->roles->isNotEmpty() ? $user->roles->first()->name : 'No role assigned') . '</td>
                <td class="d-flex align-items-center">
                    <form class="d-inline" style="scale: 150%" action="' . route('admin.users.destroy', $user) . '" onsubmit="return confirm(`o\'chirishni tasdiqlang`)" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button class="text-danger" style="border: 0;background-color: white;padding: 0;" type="submit"><i class="fa-solid fa-trash"></i></button>
                    </form>
                    &nbsp; &nbsp;
                    <form class="d-flex align-items-center" action="' . route('admin.users.role.update', $user) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('PUT') . '
                        <select name="role_id" style="height: 30px!important;" class="form-control p-1 d-inline">
                            ' . $this->getRolesOptions($user) . '
                        </select>
                        &nbsp;&nbsp;
                        <button class="text-success d-inline" style="border: 0;background-color: white;padding: 0;" type="submit"><i class="fa-solid fa-check"></i></button>
                    </form>
                </td>
            </tr>
        ';
        }
        return response()->json($output);
    }


    private function getRolesOptions($user)
    {
        $roles = \App\Models\Role::all();
        $options = '';

        foreach ($roles as $role) {
            if ($role->name !== 'Admin') {
                $selected = $user->roles->first() && $user->roles->first()->id == $role->id ? 'selected' : '';
                $options .= '<option value="' . $role->id . '" ' . $selected . '>' . $role->name . '</option>';
            }
        }
        $options .= '<option value="0" ' . (!$user->roles->isNotEmpty() ? 'selected' : '') . '>No role assigned</option>';

        return $options;
    }


    public function updateRole(Request $request, User $user)
    {
        if ($request->role_id != 0) {
            Group::where('user_id', '=', $user->id)->delete();
            Answer::where('user_id', '=', $user->id)->where('answer', '=', null)->delete();
        }
        if ($request->role_id == 0) {
            DB::table('role_user')->where('user_id', $user->id)->delete();
            return redirect()->route('admin.dashboard');
        } else {
            $user->roles()->sync([$request->role_id]);
            return redirect()->route('admin.dashboard');
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
}
