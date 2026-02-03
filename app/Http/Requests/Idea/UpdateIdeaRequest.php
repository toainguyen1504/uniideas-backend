<?php

namespace App\Http\Requests\Idea;

use App\Enum\IdeaStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateIdeaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Check quyền sửa idea
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('ideas', 'slug')->ignore($this->route('idea')->id),
            ],
            'file_path' => [
                'nullable',
                'file',
                'max:2048',
            ],
            'status' => [
                'sometimes',
                new Enum(IdeaStatus::class),
            ],
            'is_anonymous' => [
                'boolean',
            ],
            'user_id' => [
                'sometimes',
                'exists:users,id',
            ],
            'category_id' => [
                'sometimes',
                'exists:categories,id',
            ],
            'submission_id' => [
                'sometimes',
                'exists:submissions,id',
            ],
        ];
    }
}
