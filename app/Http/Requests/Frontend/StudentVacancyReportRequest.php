<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StudentVacancyReportRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => 'required',
            'file' => 'required|mimes:pdf|max:10240',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('The name field is required'),
            'file.required' => __('The file field is required'),
            'file.mimes' => __('The file must be a file of type: pdf.'),
            'file.max' => __('The file may not be greater than 10MB.'),
        ];
    }
}
