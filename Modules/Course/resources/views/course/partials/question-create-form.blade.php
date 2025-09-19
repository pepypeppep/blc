@extends('admin.master_layout')

@section('custom_meta')
    <meta name="course_id" content="{{ request('id') }}">
@endsection

@section('title')
    <title>{{ __('Course Create') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">Quiz</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Sub Category List') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <h3>Buat Quiz</h3>
                        <form action="{{ route('admin.course-chapter.lesson.store') }}" method="POST"
                            class="add_lesson_form instructor__profile-form">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $courseId }}">
                            <input type="hidden" name="chapter_id" value="{{ $chapterId }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <label for="chapter">{{ __('Chapter') }} <code>*</code></label>
                                        <select name="chapter" id="chapter" class="chapter form-control">
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach ($chapters as $chapter)
                                                <option @selected($chapterId == $chapter->id) value="{{ $chapter->id }}">
                                                    {{ $chapter->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-grp">
                                        <label for="title">{{ __('Title') }} <code>*</code></label>
                                        <input id="title" name="title" type="text" value=""
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-grp">
                                        <label for="time_limit">{{ __('Time Limit') }}
                                            <br /> <code>
                                                (diisi {{ __('in minutes') }})</code>
                                        </label>
                                        <input id="time_limit" name="time_limit" value="" class="form-control"
                                            type="number" min="1" step="1" pattern="[0-9]*"
                                            class="form-control"
                                            onkeydown="return !['e', 'E', '+', '-', '.'].includes(event.key);"
                                            oninput="this.value = this.value.replace(/[eE\+\-\.]/g, '');">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-grp">
                                        <label for="attempts">{{ __('Attempts') }} <br /> <code>
                                                ({{ __('leave empty for unlimited') }})</code></label>
                                        <input id="attempts" name="attempts" value="" class="form-control"
                                            type="number" min="1" step="1" pattern="[0-9]*"
                                            class="form-control"
                                            onkeydown="return !['e', 'E', '+', '-', '.'].includes(event.key);"
                                            oninput="this.value = this.value.replace(/[eE\+\-\.]/g, '');">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-grp">
                                        <label for="due_date">{{ __('Due Date') }}
                                            <br> <code>
                                                &nbsp;</code> </label>
                                        <input id="due_date" name="due_date" type="date" value=""
                                            class="form-control ">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-grp">
                                        <label for="total_mark">{{ __('Total mark') }} <code>*</code></label>
                                        <input id="total_mark" name="total_mark" type="number" min="1"
                                            step="1" pattern="[0-9]*" class="form-control"
                                            onkeydown="return !['e', 'E', '+', '-', '.'].includes(event.key);"
                                            oninput="this.value = this.value.replace(/[eE\+\-\.]/g, '');" value=""
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-grp">
                                        <label for="pass_mark">{{ __('Pass mark') }} <code>*</code></label>
                                        <input id="pass_mark" name="pass_mark" type="number" min="1" step="1"
                                            pattern="[0-9]*" class="form-control"
                                            onkeydown="return !['e', 'E', '+', '-', '.'].includes(event.key);"
                                            oninput="this.value = this.value.replace(/[eE\+\-\.]/g, '');" value=""
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary submit-btn">{{ __('Create') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
