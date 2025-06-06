@extends('admin.master_layout')
@section('title')
    <title>{{ __('Category List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">{{ __('Course') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Sub Category List') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12">
                            @include('course::course.navigation')
                            <div class="instructor__profile-form-wrap mt-4">
                                <form action="{{ route('admin.courses.update') }}"
                                    class="instructor__profile-form course-form">
                                    @csrf
                                    <input type="hidden" name="course_id" id="" value="{{ $course->id }}">
                                    <input type="hidden" name="step" id="" value="4">
                                    <input type="hidden" name="next_step" value="4">

                                    <div class="row">

                                        <div class="col-md-6">
                                            <label for="instansi_id" class="form-label">Instansi</label>
                                            <input type="hidden" id="instansi_id_hidden" name="instansi_id" />

                                            <select id="instansi_id" class="form-select select2"
                                                style="width: 100%;"></select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="unit_id" class="form-label">Unit Organisasi</label>
                                                <select id="unit_id" class="form-select select2 w-100"></select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="jabatan" class="form-label">Jabatan</label>
                                                <select id="jabatan" class="form-select select2 w-100">
                                                    <option value="">Pilih Jabatan</option>
                                                    @foreach ($jabatans as $key => $value)
                                                        <option value="{{ $value }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="ninebox" class="form-label">9 Box</label>
                                                <select id="ninebox" class="form-select select2 w-100">
                                                    <option value="">Pilih Box</option>
                                                    @for ($i = 1; $i <= 9; $i++)
                                                        <option value="{{ $i }}">{{ $i }} Box
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="d-flex justify-content-between">
                                                    <label for="">{{ __('Select course participant') }}</label>
                                                    <span>{{ __('Number of Participants') }} : <span class="text-danger"
                                                            id="participant_count">{{ count($course->enrollments) }}</span></span>
                                                </div>
                                                <select class="select2 participant_select form-control"
                                                    name="participants[]" multiple="multiple">
                                                    @foreach ($course?->enrollments as $enrollment)
                                                        <option value="{{ $enrollment->user->id }}" selected="selected">
                                                            {{ $enrollment->user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">{{ __('Message for Reviewer') }}
                                                    <code></code></label>
                                                <textarea name="message_for_reviewer" class="form-control">{{ $course->message_for_reviewer }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">{{ __('Status') }} <code>*</code></label>
                                                <select name="status" id="" class="form-control">
                                                    <option value="">{{ __('Select') }}</option>
                                                    <option @selected($course->status == 'active') value="active">{{ __('Publish') }}
                                                    </option>
                                                    <option @selected($course->status == 'inactive') value="inactive">
                                                        {{ __('UnPublish') }}</option>
                                                    <option @selected($course->status == 'is_draft') value="is_draft">
                                                        {{ __('Draft') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
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
@push('styles')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #5751e1;
            border: 1px solid #4943bd;
            color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
        }
    </style>
@endpush
