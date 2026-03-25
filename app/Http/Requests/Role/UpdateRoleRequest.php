<?php

namespace App\Http\Requests\Role;

use App\Acl\Acl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_ROLE_EDIT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'permissions' => [
                'array',
            ],
            'permissions.*' => [
                'string', 
                'exists:permissions,name'
            ],
        ];
    }
}
