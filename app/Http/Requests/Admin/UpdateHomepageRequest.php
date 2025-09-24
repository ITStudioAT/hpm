<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateHomepageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:homepages,id',
            'homepage_id' => 'sometimes|nullable|integer|exists:homepages,id',
            'name' => 'required|max:255',
            'path' => 'nullable|max:255',
            'type' => 'nullable|max:255',
            'structure' => 'nullable|array'
        ];
    }
}
