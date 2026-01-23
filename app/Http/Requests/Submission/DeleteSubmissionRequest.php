<?php

namespace App\Http\Requests\Submission;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('delete submissions');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Không có rules vì chỉ kiểm tra authorization
        ];
    }

    /**
     * Get the response for a forbidden operation.
     */
    public function failedAuthorization()
    {
        abort(403, 'Không thể xóa submission khi có ideas đính kèm.');
    }
}
