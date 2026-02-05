<?php

namespace App\Http\Requests\Idea;

use App\Acl\Acl;
use App\Enum\IdeaStatus;
use App\Enum\AnonymousEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreIdeaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_IDEA_ADD);
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
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
            'is_anonymous' => ['nullable',new Enum(AnonymousEnum::class)],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'submission_id' => [
                'required',
                'integer',
                'exists:submissions,id',
            ],
        ];
    }
}
