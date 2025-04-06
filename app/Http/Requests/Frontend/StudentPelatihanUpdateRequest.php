<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StudentPelatihanUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'category' => 'required|in:blog,document,video',
            'enrollment' => 'nullable|exists:enrollments,id',
            'title' => 'required',
            'thumbnail' => 'mimes:jpg,jpeg,png|max:2048',
            'visibility' => 'required|in:public,internal',
            'link' => 'required_if:category,video',
            'file' => 'required_if:category,document|mimes:pdf|max:10240',
            'content' => 'required',
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
            'thumbnail.required' => __('The thumbnail field is required'),
            'visibility.required' => __('The visibility field is required'),
            'file.required_if' => __('The file field is required'),
            'content.required' => __('The content field is required'),
            'category.in' => __('The selected category is invalid'),
            'enrollment.exists' => __('The selected enrollment id is invalid'),
            'thumbnail.mimes' => __('The thumbnail must be a file of type: jpg, jpeg, png'),
            'thumbnail.max' => __('The thumbnail may not be greater than 2048 kilobytes'),
            'file.mimes' => __('The file must be a file of type: pdf'),
            'file.max' => __('The file may not be greater than 10240 kilobytes'),
            'visibility.in' => __('The selected visibility is invalid'),
            'link.required_if' => __('The link field is required when category is video'),
            'file.required_if' => __('The file field is required when category is document'),
            'content.required' => __('The content field is required'),
            'tags.max' => __('The tags may not be greater than 5 items'),
        ];
    }
}
