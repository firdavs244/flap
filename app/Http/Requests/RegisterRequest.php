<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
//            'name' => 'required|min:3|max:100|string|regex:/^[A-Za-z]{3}[A-Za-z0-9]+$/|unique:users,name',
            'name' => 'required|min:3|max:30|string|regex:/^[A-Za-z]{3}[A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
        ];
    }
}
