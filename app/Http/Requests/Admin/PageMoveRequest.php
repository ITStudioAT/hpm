<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PageMoveRequest extends FormRequest
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
            'homepage_id' => 'required|exists:homepages,id',
            'folder_id' => 'required|exists:homepages,id',
            'move_action' => 'required|string|in:all,active',
            'page_id' => 'nullable|integer|exists:homepages,id',
            'from_folder' => 'required|string|max:255',
            'to_folder' => 'required|string|max:255',
        ];
    }
}
