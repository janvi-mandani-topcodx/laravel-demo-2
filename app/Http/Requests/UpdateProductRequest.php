<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required' ,
            'description' => 'required',
            'variant_title.*' => 'required',
            'price.*' => 'required',
            'sku.*' => 'required',
            'wholesaler_price.*' => 'nullable',
        ];
    }
    public function messages(): array{
        return [
            'title.required' => 'Product Title is required',
            'description.required' => 'Product Description is required',
            'variant_title.*.required' => 'Product Variant Title is required',
            'price.*.required' => 'Product Price is required',
            'sku.*.required' => 'Product SKU is required',
            'wholesaler_price.*.required' => 'Product Wholesaler Price is required',
        ];
    }

}
