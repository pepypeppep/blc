<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StudentPelatihanStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'category' => 'required|in:blog,document,video',
            'enrollment' => 'nullable|exists:enrollments,id',
            'certificateRecognition' => 'nullable|exists:personal_certificate_recognitions,id',
            'title' => 'required',
            'description' => 'required',
            'thumbnail' => 'required|mimes:jpg,jpeg,png|max:2048',
            'visibility' => 'required|in:public,internal',
            'link' => 'required_if:category,video',
            'file' => 'nullable|mimes:pdf|max:10240',
            'content' => 'required_if:category,blog',
            'tags' => 'nullable|array|max:5',
            'tags.*' => 'required|string',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'category.required' => __('The category field is required'),
            'title.required' => __('The title field is required'),
            'description.required' => __('The description field is required'),
            'thumbnail.required' => __('The thumbnail field is required'),
            'visibility.required' => __('The visibility field is required'),
            'file.required_if' => __('The file field is required when category is document'),
            'content.required_if' => __('The content field is required when category is blog'),
            'category.in' => __('The selected category is invalid'),
            'enrollment.exists' => __('The selected enrollment id is invalid'),
            'certificateRecognition.exists' => __('The selected certificate recognition id is invalid'),
            'thumbnail.mimes' => __('The thumbnail must be a file of type: jpg, jpeg, png'),
            'thumbnail.max' => __('The thumbnail may not be greater than 2048 kilobytes'),
            'file.mimes' => __('The file must be a file of type: pdf'),
            'file.max' => __('The file may not be greater than 10240 kilobytes'),
            'visibility.in' => __('The selected visibility is invalid'),
            'link.required_if' => __('The link field is required when category is video'),
            'tags.max' => __('The tags may not be greater than 5 items'),
        ];
    }
}
