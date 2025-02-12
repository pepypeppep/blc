@extends('admin.master_layout')
@section('title')
    <title>{{ __('Course Create') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">{{ __('Course') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Course Create') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('course::course.navigation')

                        <div class="card">
                            <div class="card-body">
                                <div class="instructor__profile-form-wrap">
                                    <form action="{{ route('admin.courses.update') }}"
                                        class="instructor__profile-form course-form">
                                        @csrf
                                        <input type="hidden" name="course_id" id="" value="{{ $courseId }}">
                                        <input type="hidden" name="step" id="" value="2">
                                        <input type="hidden" name="next_step" value="3">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="capacity">{{ __('Capacity') }} <code></code></label>
                                                    <input id="capacity" name="capacity" class="form-control"
                                                        type="text" value="{{ $course?->capacity }}">
                                                    <code>{{ __('leave blank for unlimited') }}</code>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="course_duration">{{ __('Course Duration (Minutes)') }}
                                                        <code>*</code></label>
                                                    <input id="course_duration" name="course_duration" class="form-control"
                                                        type="number" value="{{ $course?->duration }}"
                                                        oninput="this.value = Math.max(this.value, 1); this.value = (this.value < 1) ? 1 : this.value;"
                                                        min="1" step="1" pattern="[0-9]*"
                                                        title="{{ __('Only allow numbers') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="category">{{ __('Category') }}<span
                                                            class="text-danger">*</span></label>
                                                    <select class="select2 form-group category" name="category">
                                                        <option value="">{{ __('Select') }}</option>
                                                        @foreach ($categories as $category)
                                                            @if ($category->subCategories->isNotEmpty())
                                                                <optgroup label="{{ $category->translation?->name }}">
                                                                    @foreach ($category->subCategories as $subCategory)
                                                                        <option @selected($course?->category_id == $subCategory->id)
                                                                            value="{{ $subCategory->id }}">
                                                                            {{ $subCategory->translation?->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @error('category')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 my-4">
                                                <div class="form-group">
                                                    <label for="from_date_start">Pelaksanaan Pembelajaran
                                                        <code>*</code></label>
                                                    <div class="input-group input-daterange">
                                                        <input type="text" name="start_date" class="form-control"
                                                            value="{{ $course?->start_date }}">
                                                        <span class="input-group-text mx-2">{{ __('Sampai') }}</span>
                                                        <input type="text" name="end_date" class="form-control"
                                                            value="{{ $course?->end_date }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 my-4">
                                                <div class="form-group">
                                                    <label for="level">{{ __('Level') }}
                                                        <code>*</code></label>
                                                    @php
                                                        $courseLevel = $course->levels->pluck('level_id')->toArray();
                                                    @endphp
                                                    <select class="select2 form-group" name="levels[]">
                                                        <option value="">{{ __('Select') }}</option>
                                                        @foreach ($levels as $level)
                                                            <option @selected(in_array($level->id, $courseLevel))
                                                                value="{{ $level->id }}">
                                                                {{ $level->translation?->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="output">{{ __('Course Output') }}
                                                        <code>*</code></label>
                                                    <textarea name="output" class="text-editor form-control summernote">{!! clean(@$course?->output) !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="outcome">{{ __('Course Outcome') }}
                                                        <code>*</code></label>
                                                    <textarea name="outcome" class="text-editor form-control summernote">{!! clean(@$course?->outcome) !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
    </section>
    </div>
@endsection

@push('js')
    <script src="{{ asset('backend/js/default/courses.js') }}"></script>

    <script>
        $(document).ready(function() {
            const $name = $("#title"),
                $slug = $("#slug");

            $name.on("keyup", function(e) {
                $slug.val(convertToSlug($name.val()));
            });

            function convertToSlug(text) {
                return text
                    .toLowerCase()
                    .replace(/[^a-z\s-]/g, "") // Remove all non-word characters (except -)
                    .replace(/\s+/g, "-") // Replace spaces with -
                    .replace(/-+/g, "-"); // Replace multiple - with single -
            }
        })
        $('.input-daterange').datepicker({
            todayBtn: "linked",
            clearBtn: true,
            autoclose: false,
            format: "yyyy-mm-dd"
        });
    </script>
@endpush

@push('css')
    <style>
        .dd-custom-css {
            position: absolute;
            will-change: transform;
            top: 0px;
            left: 0px;
            transform: translate3d(0px, -131px, 0px);
        }

        .max-h-400 {
            min-height: 400px;
        }
    </style>
@endpush
