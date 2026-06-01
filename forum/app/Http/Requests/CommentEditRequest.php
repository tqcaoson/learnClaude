<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentEditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'min:1', 'max:1000'],
        ];
    }
}
