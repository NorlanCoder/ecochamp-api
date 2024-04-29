<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'requied',
            'title' => 'requied',
            'message' => 'requied',
            'country' => 'requied',
            'city' => 'requied',
            'distributed_to' => 'requied',
            'type_id' => 'requied',
            'status' => 'requied',
            'start_date' => 'requied',
            'end_date' => 'requied',
        ];
    }
}
