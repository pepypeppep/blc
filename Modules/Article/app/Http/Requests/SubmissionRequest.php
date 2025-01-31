<?php

namespace Modules\Article\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'status' => 'required',
        ];
        return $rules;
    }

    public function messages(): array
    {
        return [
            'status.required' => __('The status is required.'),
        ];
    }
}
