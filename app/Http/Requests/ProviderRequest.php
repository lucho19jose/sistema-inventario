<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
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
            'name' => 'required',
            'ruc' => '',
            'address' => 'required',
            'phone' => 'required|numeric|min:100000000|max:999999999',
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return[
            'name.required' => 'Ingrese el nombre del proveedor.',
            'address.required' => 'Ingrese direccion del proveedor.',
            'phone.required' => 'El telefono no puede estar vacio',
            'phone.min' => 'El telefono debe contener 9 digitos',
            'phone.max' => 'El telefono debe contener 9 digitos',
            'email.email' => 'Ingrese un email valido',
            //'email.unique' => 'El email ya se encuentra registrado',
        ];
    }
}
