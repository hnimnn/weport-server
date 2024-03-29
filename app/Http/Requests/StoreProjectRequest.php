<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
            'thumbnail' => ['required'],
            'user_id' =>  ['required'],
            'description' => 'nullable',
            'tags' => 'nullable',
            'price' => ['required', 'min:0'],
            'source' => ['required'],
            'status' => 'nullable'

        ];
    }
}
