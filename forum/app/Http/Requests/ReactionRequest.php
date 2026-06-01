<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'target_type' => ['required', 'in:post,comment'],
            'target_id' => ['required', 'integer', 'min:1'],
        ];
    }
}
