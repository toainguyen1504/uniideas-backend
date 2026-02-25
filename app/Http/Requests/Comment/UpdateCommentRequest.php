<?php

namespace App\Http\Requests\Comment;

use App\Enum\AnonymousEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateCommentRequest extends FormRequest
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
            'idea_id' => [
                'required',
                'integer',
                'exists:ideas,id',
            ],
            'content' => [
                'required',
                'string',
                'max:1000',
            ],
            'is_anonymous' => [
                'nullable',
                'integer',
                new Enum(AnonymousEnum::class),
            ]
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->user()) {
            $this->merge([
                'user_id' => $this->user()->id,
            ]);
        }
    }
}
