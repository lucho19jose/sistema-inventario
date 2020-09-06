<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffUpdateRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name'  => 'required',
            'dni'  => 'required|numeric|min:10000000|max:99999999',
            'address'  => 'required',
            'phone'  => 'required|numeric|min:100000000|max:999999999',
            'email'  => 'required|email|unique:staff,email,' . $this->staff,
        ];
    }

    public function messages()
    {
        return[
            'first_name.required' => 'Ingrese el nombre del personal.',
            'last_name.required'  => 'Ingrese el apellido del personal.',
            'dni.required'  => 'Ingrese el DNI del personal.',
            'dni.min'  => 'El DNI debe contener 8 digitos.',
            'dni.max'  => 'El DNI debe contener 8 digitos.',
            'address.required'  => 'Ingrese la direccion del personal.',
            'phone.required' => 'El telefono no puede estar vacio.',
            'phone.min' => 'El telefono debe contener 9 digitos.',
            'phone.max' => 'El telefono debe contener 9 digitos.',
            'email.email' => 'Ingrese un email valido.',
            'email.unique' => 'El email ya se encuentra en uso.'
        ];
    }
}
