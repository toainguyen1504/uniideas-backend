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
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                'max:10240',
            ],
            'is_anonymous' => [
                'nullable',
                new Enum(AnonymousEnum::class)
            ],
            'category_id' => [
                'sometimes',
                'integer',
            ],
            'submission_id' => [
                'sometimes',
                'integer',
            ],
            'intro' => [
                'sometimes',
                'string'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file_path.mimes' => 'The file must be a file of type: pdf, doc, docx, xls, xlsx, jpg, jpeg, png.',
            'file_path.max' => 'The file may not be greater than 10MB.',
        ];
    }
}
