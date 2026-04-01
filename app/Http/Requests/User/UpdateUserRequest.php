<?php

namespace App\Http\Requests\User;

use App\Acl\Acl;
use App\Enum\GenderEnum;
use App\Enum\UserStatus;
use App\Rules\ValidPhoneNumber;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_USER_EDIT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "first_name" => [
                "required", 
                "string", 
                "max:30"
            ],
            "last_name" => [
                "required", 
                "string", 
                "max:30"
            ],
            "phone_number" => [
                "required",
                new ValidPhoneNumber(),
            ],
            "email" => [
                "required", 
                "email",
                "max:100",
            ],
            "birth_date" => [
                "nullable", 
                "date"
            ],
            "password" => [
                "nullable", 
                "string", 
                "min:8"
            ],
            "roles" => [
                "required",
                "integer",
            ],
            "status" => [
                "required", 
                new Enum(UserStatus::class)
            ],
            'department_id' => [
                'integer',
                'required',
            ],
        ];
    }
}
