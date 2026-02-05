<?php

namespace App\Http\Requests\Idea;

use App\Enum\AnonymousEnum;
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
            'file_path' => [
                'nullable',
            ],
            'status' => [
                'nullable',
                'integer',
                new Enum(IdeaStatus::class),
            ],
            'is_anonymous' => [ 'nullable',new Enum(AnonymousEnum::class)],
            'user_id' => [
                'sometimes',
                'integer',
                'exists:users,id',
            ],
            'category_id' => [
                'sometimes',
                'integer',
                'exists:categories,id',
            ],
            'submission_id' => [
                'sometimes',
                'integer',
                'exists:submissions,id',
            ],
        ];
    }
}
