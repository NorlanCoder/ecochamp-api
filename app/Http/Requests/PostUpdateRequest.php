<?php

namespace App\Http\Requests;

use App\Enums\Enums\Distributed_to;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
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
            'post_id' => 'required',
            'title' => 'required',
            'message' => 'required',
            'distributed_to' => 'required',
            'type_id' => 'exists:App\Models\PostType,id',
            'start_date' => 'date',
            'end_date' => 'required_with:start_date|date|after:start_date',
            'medias.*' => 'file',
            'tags' => 'array',
            'actions' => 'array',
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
            'id.required' => 'L\'id est nécessaire',
            'title.required' => 'Un titre est nécessaire',
            'message.required' => 'Un message est nécessaire',
            'type_id.required' => 'Le type id est nécessaire',
            'tags.array' => 'Les tags doivent etre dans un tableau',
            'action.array' => 'Les actions doivent etre dans un tableau',
            'medias.*.file' => 'Ca doit etre un traveau de fichier telecharger',
        ];
    }
}
