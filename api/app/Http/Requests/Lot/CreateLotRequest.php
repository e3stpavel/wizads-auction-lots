<?php

namespace App\Http\Requests\Lot;

use Illuminate\Foundation\Http\FormRequest;

class CreateLotRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|not_in:"__root__"',
            'price' => 'required|numeric|gt:0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
