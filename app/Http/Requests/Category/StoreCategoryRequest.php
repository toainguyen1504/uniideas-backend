<?php

namespace App\Http\Requests\Category;

use App\Acl\Acl;
use App\Enum\CategoryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_CATEGORY_ADD);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'status' => ['required', Rule::in(CategoryStatus::values())],
        ];
    }
}