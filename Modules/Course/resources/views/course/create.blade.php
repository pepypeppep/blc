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
                    <div class="breadcrumb-item">{{ __('Course') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="dashboard__content-wrap">
                    <div class="row">
                        <div class="col-12">
                            @include('course::course.navigation')

                            <div class="card">
                                <div class="card-body">
                                    <div class="instructor__profile-form-wrap mt-4">
                                        <form action="{{ route('admin.courses.store', ['id' => @$course?->id]) }}"
                                            class="instructor__profile-form course-form">
                                            @csrf
                                            <input type="hidden" name="step" value="1">
                                            <input type="hidden" name="next_step" value="2">
                                            <input type="hidden" name="edit_mode"
                                                value="{{ isset($editMode) && $editMode == true ? true : false }}">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="instructor">{{ __('Instructor') }}
                                                            <code>*</code></label>
                                                        <select name="instructor" id=""
                                                            class="form-control select2">
                                                            <option value="">{{ __('Select') }}</option>
                                                            @foreach ($instructors as $instructor)
                                                                <option value="{{ $instructor->id }}"
                                                                    @selected($instructor->id == @$course?->instructor_id)>{{ $instructor->name }}
                                                                    ({{ $instructor->email }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="instansi">instansi
                                                            <code>*</code></label>
                                                        <select name="instansi" id="" class="form-control select2">
                                                            <option value="">{{ __('Select') }}</option>
                                                            @foreach ($instansis as $instansi)
                                                                <option value="{{ $instansi->id }}"
                                                                    @selected($instansi->id == @$course?->instansi_id)>{{ $instansi->name }}
                                                                    ({{ $instansi->name }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="title">{{ __('Title') }} <code>*</code></label>
                                                        <input id="title" name="title" type="text"
                                                            class="form-control" value="{{ @$course?->title }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="from-group mb-3">
                                                        <label class="form-file-manager-label"
                                                            for="">{{ __('Thumbnail') }}
                                                            <code>*</code></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon1"
                                                                onclick="$('#thumbnail').trigger('click');">
                                                                <a data-input="thumbnail" data-preview="holder">
                                                                    <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                                                                </a>
                                                            </span>
                                                            <input id="thumbnail_name" readonly class="form-control"
                                                                type="text" name="thumbnail_name"
                                                                value="{{ @$course?->thumbnail }}"
                                                                onclick="$('#thumbnail').trigger('click');">
                                                            <input id="thumbnail" class="form-control d-none" type="file"
                                                                onchange="$('#thumbnail_name').val(this.files[0].name)"
                                                                name="thumbnail" value="{{ @$course?->thumbnail }}"
                                                                accept=".jpg, .jpeg, .png">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="price">Jam Pelajaran (JP)
                                                            <code>*</code></label>
                                                        <input id="price" name="price" type="text"
                                                            class="form-control" value="{{ @$course?->price }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">{{ __('Course Purpose') }}
                                                            <code>*</code></label>
                                                        <textarea name="description" class="text-editor form-control summernote">{!! clean(@$course?->description) !!}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="background">{{ __('Background') }}
                                                            <code>*</code></label>
                                                        <textarea name="background" class="text-editor form-control summernote">{!! clean(@$course?->background) !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                        </form>
                                    </div>
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
