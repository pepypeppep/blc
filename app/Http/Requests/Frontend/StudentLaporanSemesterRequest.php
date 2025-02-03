<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StudentLaporanSemesterRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'judul' => 'required',
            'semester' => 'required',
            'berkas' => 'required|mimes:pdf|max:10240',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'vacancy_detail_id.required' => __('The vacancy detail field is required.'),
            'vacancy_user_id.required' => __('The vacancy user field is required.'),
            'file.mimes' => __('The file must be a file of type: pdf.'),
            'file.max' => __('The file may not be greater than 10MB.'),
        ];
    }
}
