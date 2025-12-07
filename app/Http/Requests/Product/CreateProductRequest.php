<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name'=>[
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'description'=>[
                'nullable',
                'string',
            ],
            'preview'=>[
                'nullable',
                'string'
            ],
            'country_id'=>[
                'required',
                'integer',
                'exists:countries,id'
            ],
            'tags'=>[
                'nullable',
                'array'
            ],
            'tags.*'=>[
                'integer',
                'exists:tags,id'
            ]
        ];
    }
}
