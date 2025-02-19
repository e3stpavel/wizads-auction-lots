<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LotRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'price' => 'required|numeric',
            'children' => 'nullable|json',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
