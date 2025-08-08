<?php

namespace admin\pages\Requests;

use Illuminate\Foundation\Http\FormRequest;
use tidy;

class PageCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [                   
            'title' => 'required|string|min:3|max:100|unique:pages,title',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'content' => 'required|string|min:3|max:65535',
            'status' => 'required|in:draft,published', // Ensure status is one of the allowed values
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
