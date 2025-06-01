@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Pengumuman') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Create Pengumuman') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a href="{{ route('admin.blogs.index') }}">{{ __('Blog List') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Create Pengumuman') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Create Pengumuman') }}</h4>
                                <div>
                                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-primary"><i
                                            class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.pengumuman.store') }}" method="POST">
                                    @csrf
                                    <div class="row">

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Title') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="title" class="form-control" name="title"
                                                value="{{ old('title') }}" required>
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Content') }} <span class="text-danger">*</span></label>
                                            <textarea name="content" id="" cols="30" rows="10" class="summernote">{{ old('content') }}</textarea>
                                            @error('content')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>
                                                <input type="hidden" name="status" class="custom-switch-input"
                                                    value="0">
                                                <input type="checkbox" name="status" class="custom-switch-input"
                                                    value="1" {{ old('status') == 1 ? 'checked' : '' }}>
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">{{ __('Show on homepage') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="text-center col-md-8 offset-md-2">
                                            <x-admin.save-button :text="__('Save')"></x-admin.save-button>
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