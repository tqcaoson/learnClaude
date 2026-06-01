<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'status' => ['required', 'in:published,draft'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'tags' => ['nullable', 'array', 'max:10'],
            'tags.*' => ['string', 'max:50'],
            'board_id' => ['nullable', 'integer', 'exists:boards,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.min' => 'Title must be at least 3 characters',
            'content.required' => 'Content is required',
            'content.min' => 'Content must be at least 10 characters',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be published or draft',
        ];
    }
}
