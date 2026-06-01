<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:100',
            'description' => 'nullable|max:1000',
            'type' => 'required|in:public,private',
            'avatar' => 'nullable|image|max:2048',
        ];
    }
}
