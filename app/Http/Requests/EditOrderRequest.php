<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'state' => 'required',
            'country' => 'required',
            'delivery' => 'required',
        ];
    }
}
