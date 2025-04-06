<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StudentActivationRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'file' => 'required|mimes:pdf|max:5120',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'file.required' => __('File harus diisi.'),
            'file.mimes' => __('File harus berupa file PDF.'),
            'file.max' => __('Ukuran file tidak boleh lebih dari 5 MB.'),
        ];
    }
}
