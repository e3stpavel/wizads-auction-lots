<?php

namespace App\Http\Requests\Lot;

use Illuminate\Foundation\Http\FormRequest;

class PatchLotRequest extends FormRequest
{
    public function rules(): array
    {
        // kinda JSON patch but not really...
        //  https://datatracker.ietf.org/doc/html/rfc6902
        return [
            'operation' => 'required|in:replace',
            'path' => 'required|in:"/containedLots"',
            'value' => 'required|list',
            'value.*' => 'numeric|integer|distinct:strict',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
