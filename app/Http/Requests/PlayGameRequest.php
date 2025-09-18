<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayGameRequest extends FormRequest
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
            'choice' => 'required|string|in:rock,paper,scissors',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'choice.required' => 'Please make a choice to play.',
            'choice.in' => 'Invalid choice. Please select rock, paper, or scissors.',
        ];
    }
}