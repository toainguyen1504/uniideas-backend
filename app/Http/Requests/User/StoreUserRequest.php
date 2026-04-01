<?php

namespace App\Http\Requests\User;

use App\Acl\Acl;
use App\Enum\GenderEnum;
use App\Enum\UserStatus;
use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_USER_ADD);
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
                "unique:users,phone_number",
                new ValidPhoneNumber(),
            ],
            "email" => [
                "required", 
                "email", 
                "max:100", 
                "unique:users,email"
            ],
            "birth_date" => [
                "nullable", 
                "date"
            ],
            "password" => [
                "required", 
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
