<?php

namespace App\Http\Requests\User;

use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use App\Enum\GenderEnum;
use Illuminate\Validation\Rules\Enum;

class UpdateProfileRequest extends FormRequest
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
            'first_name' => [
                'nullable', 
                'string', 
                'max:30'
            ],
            'last_name' => [
                'nullable', 
                'string', 
                'max:30'
            ],
            'phone_number' => [
                'nullable',
                new ValidPhoneNumber(),
            ],
            'birth_date' => [
                "nullable", 
                "date"
            ],
            'password' => [
                "nullable", 
                "string", 
                "min:8",
                "confirmed"
            ],
        ];
    }
}
