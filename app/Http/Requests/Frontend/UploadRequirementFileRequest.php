<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequirementFileRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            // 'attachment_id' => 'required',
            'file' => 'required|mimes:pdf|max:10240',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            // 'vacancy_id.required' => __('The vacancy id field is required'),
            // 'attachment_id.required' => __('The attachment id field is required'),
            'file.required' => __('The file field is required'),
        ];
    }
}
