<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'code' => 'required|unique:products,code,' . $this->product,
            'category_id' => 'required|integer',
            'name' => 'required',
            'description' => '',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Ingrese el codigo del producto.',
            'code.unique' => 'El codigo ya se encuentra en uso.',
            'category_id.integer' => 'Seleccione una categoría.',
            'name.required' => 'El producto necesita un nombre.',
            'stock.required' => 'El stock del producto no puede estar vacío.',
            'stock.integer' => 'El stock del producto debe ser un número.',
            'stock.min' => 'El stock del producto debe ser mínimo 0.',
        ];
    }
}
