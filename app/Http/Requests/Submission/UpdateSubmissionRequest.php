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
                 'date_format:"j-n-Y H:i"',
                function ($attribute, $value, $fail) {
                    if (
                        $this->has('final_closure_date') &&
                        $value >= $this->final_closure_date
                    ) {
                        $fail('The closing date must be before the closing date.');
                    }
                }
            ],
            'final_closure_date' => [
                'sometimes',
                 'date_format:"j-n-Y H:i"',
                'after:closure_date',
                function ($attribute, $value, $fail) {

                    if (
                        $this->has('closure_date') &&
                        $value <= $this->closure_date
                    ) {
                        $fail('The final closing date must be after the closing date.');
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
            'name.unique' => 'The submission name already exists.',
            'closure_date.date' => 'The closure date is not in the correct format.',
            'final_closure_date.date' => 'The final closure date is not in the correct format.',
            'final_closure_date.after' => 'The final closure date must be after the closure date.',
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
