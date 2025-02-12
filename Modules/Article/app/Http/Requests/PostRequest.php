<?php

namespace Modules\Article\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|image|max:10240',
            'content' => 'required',
            'category' => 'required',
            'description' => 'required',
            'allow_comments' => 'required',
            'tags' => 'nullable|array|max:5',
            'tags.*' => 'required|string',
        ];

        if ($this->isMethod('put')) {
            $rules['thumbnail'] = 'nullable';
            $rules['content'] = 'nullable';
            $rules['description'] = 'nullable';
            $rules['allow_comments'] = 'nullable';
        }

        if ($this->isMethod('post')) {
            $rules['title'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'course_id.required' => __('The course is required.'),
            'course_id.exists' => __('The course is not exists.'),

            'title.required' => __('The title is required and must be a string with a maximum length of 255 characters.'),
            'title.string' => __('The title is required and must be a string with a maximum length of 255 characters.'),
            'title.max' => __('The title is required and must be a string with a maximum length of 255 characters.'),

            'thumbnail.required' => __('The thumbnail is required and must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
            'thumbnail.image' => __('The thumbnail must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
            'thumbnail.max' => __('The thumbnail must be an image file with a maximum size of 2048 kilobytes (2 MB).'),

            'content.required' => __('The content is required and must be a string with a maximum length of 65535 characters.'),
            'description.required' => __('The description is required and must be a string with a maximum length of 65535 characters.'),

            'allow_comments.required' => __('The allow comments is required.'),

            'category.required' => __('The category is required.'),
        ];
    }
}
