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
            ],
            'closure_date' => [
                'required',
                 'date_format:"j-n-Y H:i"',
                'after_or_equal:today' // Ngày đóng phải từ hôm nay trở đi
            ],
            'final_closure_date' => [
                'required',
                 'date_format:"j-n-Y H:i"',
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
            'name.required' => 'Submission name is required.',
            'name.unique' => 'Submission name already exists.',
            'closure_date.required' => 'Closure date is required.',
            'closure_date.after_or_equal' => 'Closure date must be today or later.',
            'final_closure_date.required' => 'Final closure date is required.',
            'final_closure_date.after' => 'Final closure date must be after the closure date.',
        ];
    }


    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'submission name',
            'closure_date' => 'closure date',
            'final_closure_date' => 'final closure date',
        ];
    }
}
