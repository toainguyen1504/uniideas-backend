<?php

namespace App\Http\Requests\Category;

use App\Enum\CategoryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($this->route('category')),
            ],
            'status' => ['sometimes', Rule::in(CategoryStatus::values())],
        ];
    }
}