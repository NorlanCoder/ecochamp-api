<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostReactionRequest extends FormRequest
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
            'post_id' => 'exists:App\Models\Post,id',
            'reaction_id' => 'exists:App\Models\Reaction,id',
            // 'user_id' => 'requied',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'post_id.exists' => 'Le post id est invalide',
            'reaction_id.exists' => 'La reaction id est invalide',
        ];
    }
}
