<?php

namespace Modules\Course\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChapterLessonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->type == 'lesson') {
            return $this->lessonRules();
        } elseif ($this->type == 'document') {
            return $this->documentRules();
        } else {
            return $this->quizRules();
        }
    }

    function lessonRules(): array
    {
        $rules = [
            'title' => ['required', 'max:255', 'string'],
            'description' => ['nullable', 'max:600', 'string'],
            'link_path' => ['required', 'url'],
            'duration' => ['required', 'numeric', 'min:1'],
        ];

        return $rules;
    }
    function documentRules(): array
    {
        $rules = [
            'chapter'     => ['required', 'exists:course_chapters,id'],
            'title'       => ['required', 'max:255'],
            'description' => ['nullable', 'max:600'],
            'file_path' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];

        return $rules;
    }

    function quizRules(): array
    {
        $rules = [
            'chapter'    => ['required', 'exists:course_chapters,id'],
            'title'      => ['required', 'max:255', 'string'],
            'time_limit' => ['nullable', 'numeric', 'min:1'],
            'attempts'   => ['nullable', 'numeric', 'min:1'],
            'due_date'  => ['required', 'date'],
            'pass_mark'  => ['required', 'numeric', 'min:1'],
            'total_mark' => ['required', 'numeric', 'min:1'],
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'title.required'       => __('The title field is required.'),
            'title.max'            => __('The title may not be greater than 255 characters.'),
            'description.required' => __('The description field is required.'),
            'description.max'      => __('The description may not be greater than 600 characters.'),
            'source.required'      => __('The source field is required.'),
            'file_type.required'   => __('The file type field is required.'),
            'chapter.required'     => __('Chapter is required'),
            'chapter.exists'       => __('Chapter doesnt exist'),
            'time_limit.required'  => __('Time limit is required'),
            'time_limit.numeric'   => __('Time limit must be a number'),
            'time_limit.min'       => __('Time limit must be at least 1 minute'),
            'attempts.required'    => __('Number of attempts is required'),
            'attempts.numeric'     => __('Number of attempts must be a number'),
            'attempts.min'         => __('Number of attempts must be at least 1'),
            'pass_mark.required'   => __('Pass mark is required'),
            'pass_mark.numeric'    => __('Pass mark must be a number'),
            'pass_mark.min'        => __('Pass mark must be at least 1'),
            'upload_path.required' => __('The upload path field is required.'),
            'upload_path.file'     => __('The upload path must be a file.'),
            'upload_path.mimes'    => __('The upload path must be a file of type: .pdf'),
            'upload_path.max'      => __('The upload path may not be greater than 10240 kilobytes (10 MB).'),
            'link_path.required' => trans('The link path field is required.'),
            'link_path.url' => trans('The link path must be a valid URL address.'),
            'duration.required' => trans('The duration field is required.'),
            'duration.numeric' => trans('The duration must be a number.'),
            'duration.min' => trans('The duration must be at least 1 minute.'),
            'due_date.required' => trans('The due date field is required.'),
            'due_date.date' => trans('The due date must be a valid date.'),
        ];

        return $messages;
    }
}
