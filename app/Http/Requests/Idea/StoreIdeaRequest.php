<?php

namespace App\Http\Requests\Idea;

use App\Acl\Acl;
use App\Enum\IdeaStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreIdeaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Check quyền thêm idea, bạn có thể định nghĩa trong Acl
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:ideas,slug',
            ],
            'content' => [
                'required',
                'string',
            ],
            'file_path' => [
                'nullable',
                'file',
                'max:2048',
            ],
            'status' => [
                'required',
                new Enum(IdeaStatus::class),
            ],
            'is_anonymous' => [
                'boolean',
            ],
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'submission_id' => [
                'required',
                'exists:submissions,id',
            ],
        ];
    }
}
