<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutputRequest extends FormRequest
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
            'staff_id' => 'required|integer|min:1',
            'branch_id' => 'required|integer|min:1',
            'voucher_type' => 'required',
            'voucher_serie' => 'required',
            'voucher_number' => 'required',
            'observation' => ''
        ];
    }

    public function messages()
    {
        return [
            'staff_id.required' => 'Seleccione un proveedor.',
            'staff_id.min' => 'Seleccione un proveedor.',
            'branch_id.required' => 'Seleccione una sucursal.',
            'branch_id.min' => 'Seleccione una sucursal.',
            'voucher_type.required' => 'Seleccione un comprobante.',
            'voucher_serie.required' => 'Ingrese el número de serie.',
            'voucher_number.required' => 'Ingrese el número del comprobante.'
        ];
    }
}
