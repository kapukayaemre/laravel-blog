<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'name'            => 'required|max:255',
            'slug'            => 'max:255',
            'description'     => 'max:255',
            'seo_keywords'    => 'max:255',
            'seo_description' => 'max:255',
            'image'           => 'image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Name is required field',
            'name.max'            => 'Max character for name is 255',
            'slug.max'            => 'Max character for slug is 255',
            'description.max'     => 'Max character for description is 255',
            'seo_keywords.max'    => 'Max character for seo keywords is 255',
            'seo_description.max' => 'Max character for seo description is 255',
        ];
    }
}
