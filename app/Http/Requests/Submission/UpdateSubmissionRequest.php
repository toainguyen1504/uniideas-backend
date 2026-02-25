<?php

namespace App\Http\Requests\Submission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubmissionRequest extends FormRequest
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
        $submissionId = $this->route('submission') ? $this->route('submission')->id : null;

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('submissions')
                    ->ignore($submissionId) 
            ],
            'closure_date' => [
                'sometimes',
                'date',
                function ($attribute, $value, $fail) {
                    if ($this->has('final_closure_date') && 
                        $value >= $this->final_closure_date) {
                        $fail('Ngày đóng phải trước ngày đóng cuối.');
                    }
                }
            ],
            'final_closure_date' => [
                'sometimes',
                'date',
                'after:closure_date',
                function ($attribute, $value, $fail) {
                    // Kiểm tra nếu final_closure_date được cập nhật, phải sau closure_date
                    if ($this->has('closure_date') && 
                        $value <= $this->closure_date) {
                        $fail('Ngày đóng cuối phải sau ngày đóng.');
                    }
                }
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Đảm bảo closure_date luôn trước final_closure_date
        if ($this->has('closure_date') && $this->has('final_closure_date')) {
            $this->merge([
                'closure_date' => $this->closure_date,
                'final_closure_date' => $this->final_closure_date,
            ]);
        }
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Tên submission đã tồn tại.',
            'closure_date.date' => 'Ngày đóng không đúng định dạng.',
            'final_closure_date.date' => 'Ngày đóng cuối không đúng định dạng.',
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