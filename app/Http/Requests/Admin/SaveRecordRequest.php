<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'record' => ['required', 'array'],
            'record.id' => ['required', 'integer', 'exists:homepages,id'],
            'record.name' => ['required', 'string', 'max:255'],
            'record.type' => ['required', 'string', 'max:255'],
            'record.path' => ['nullable', 'string', 'max:255'],
            'record.structure' => ['nullable'],
        ];
    }
}
