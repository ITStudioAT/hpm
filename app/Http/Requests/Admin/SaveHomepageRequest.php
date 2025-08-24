<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveHomepageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'homepage' => ['required', 'array'],
            'homepage.id' => ['required', 'integer', 'exists:homepages,id'],
            'homepage.name' => ['required', 'string', 'max:255'],
            'homepage.type' => ['required', 'string', 'max:255'],
            'homepage.path' => ['nullable', 'string', 'max:255'],
            // structure can be nullable, adjust as needed
            'homepage.structure' => ['nullable'],
        ];
    }
}
