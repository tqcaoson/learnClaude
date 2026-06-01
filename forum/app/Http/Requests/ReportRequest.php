<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'reportable_type' => 'required|in:post,comment',
            'reportable_id' => 'required|integer',
            'reason' => 'required|in:spam,harassment,inappropriate,misinformation,other',
            'description' => 'nullable|max:500',
        ];
    }
}
