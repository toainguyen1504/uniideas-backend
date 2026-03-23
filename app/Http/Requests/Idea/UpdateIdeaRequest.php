<?php

namespace App\Http\Requests\Idea;

use App\Acl\Acl;
use App\Enum\AnonymousEnum;
use App\Enum\IdeaStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateIdeaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_IDEA_EDIT);
    }

    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'content' => [
                'required',
                'string',
            ],
            'file_path' => [
                'nullable',
            ],
            'status' => [
                'nullable',
                'integer',
                new Enum(IdeaStatus::class),
            ],
            'is_anonymous' => [
                'nullable',
                new Enum(AnonymousEnum::class)
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

            'is_featured' => ['sometimes', 'boolean'],
            'intro'       => ['sometimes', 'string'],
        ];
    }
}
