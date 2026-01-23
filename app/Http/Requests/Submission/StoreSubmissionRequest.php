<?php

namespace App\Http\Requests\Submission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubmissionRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('submissions')->whereNull('deleted_at') // Không trùng tên với các bản ghi chưa xóa
            ],
            'closure_date' => [
                'required',
                'date',
                'after_or_equal:today' // Ngày đóng phải từ hôm nay trở đi
            ],
            'final_closure_date' => [
                'required',
                'date',
                'after:closure_date' // Ngày đóng cuối phải sau ngày đóng
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên submission là bắt buộc.',
            'name.unique' => 'Tên submission đã tồn tại.',
            'closure_date.required' => 'Ngày đóng là bắt buộc.',
            'closure_date.after_or_equal' => 'Ngày đóng phải từ hôm nay trở đi.',
            'final_closure_date.required' => 'Ngày đóng cuối là bắt buộc.',
            'final_closure_date.after' => 'Ngày đóng cuối phải sau ngày đóng.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'tên submission',
            'closure_date' => 'ngày đóng',
            'final_closure_date' => 'ngày đóng cuối',
        ];
    }
}