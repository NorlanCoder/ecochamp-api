<?php

namespace App\Http\Requests;

use App\Enums\ActionStatus;
use App\Enums\Distributed_to;
use App\Enums\PostType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PostRequest extends FormRequest
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
            'title' => 'required|string',
            'message' => 'required|string',
            'distributed_to' => [Rule::enum(Distributed_to::class)],
            'type' => [Rule::enum(PostType::class)],
            'status' => [Rule::enum(ActionStatus::class)],
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
            'title.required' => 'Un titre est nécessaire',
            'message.required' => 'Un message est nécessaire',
            'type_id.exists' => 'Le type id est nécessaire',
            'tags.array' => 'Les tags doivent etre dans un tableau',
            'action.array' => 'Les actions doivent etre dans un tableau',
            'medias.*.file' => 'Ca doit etre un traveau de fichier telecharger',
        ];
    }
}
