<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|min:4|max:60',
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Ingrese una contraseña.',
            'password.min' => 'La contraseña debe contener entre 4 a 60 caracteres.',
            'password.max' => 'La contraseña debe contener entre 4 a 60 caracteres.',
            'password_confirmation.required' => 'Ingrese nuevamente la contraseña.',
            'password_confirmation.same' => 'La contraseña no coinciden.',
        ];
    }
}
