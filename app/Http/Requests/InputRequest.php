<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InputRequest extends FormRequest
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
            'user_id' => 'required|integer',
            'provider_id' => 'required|integer|min:1',
            'voucher_type' => 'required',
            'voucher_serie' => 'required',
            'voucher_number' => 'required',
            'observation' => '',
            'total' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'provider_id.required' => 'Seleccione un proveedor.',
            'provider_id.min' => 'Seleccione un proveedor.',
            'voucher_type.required' => 'Seleccione un comprobante.',
            'voucher_serie.required' => 'Ingrese el número de serie.',
            'voucher_number.required' => 'Ingrese el número del comprobante.',
            'total.min' => 'No hay productos agregados.',
        ];
    }
}
