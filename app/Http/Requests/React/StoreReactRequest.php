<?php

namespace App\Http\Requests\React;

use App\Enum\AnonymousEnum;
use App\Enum\ReactEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreReactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
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
                'exists:ideas,id'
            ],
            'react' => [
                'nullable',
                'integer',
                new Enum(ReactEnum::class),
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
