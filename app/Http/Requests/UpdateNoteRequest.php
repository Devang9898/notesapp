<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Assuming all users can update their notes
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'tags' => 'nullable|string',
            'is_favorite' => 'nullable|boolean',
            'status' => 'required|in:active,archived',
            'reminder_at' => 'nullable|date',
            'attachments' => 'nullable|string',
        ];
    }
}
