@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Vacancy') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Create Vacancy') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.pendidikanlanjutan.index') }}">{{ __('Vacancy List') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Create Vacancy') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Create Vacancy') }}</h4>
                                <div>
                                    <a href="{{ route('admin.vacancies.index') }}" class="btn btn-primary"><i
                                            class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.vacancies.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-8 row px-0 offset-md-2">
                                            <div class="form-group col-md-8">
                                                <label>{{ __('Instansi') }} <span class="text-danger">*</span></label>
                                                <select name="instansi_id" class="form-control select2" id="instansi_id">
                                                    <option value="">Pilih Instansi</option>
                                                    @foreach ($instansi as $in)
                                                        <option value="{{ $in->id }}"
                                                            {{ $in->id == old('instansi_id') ? 'selected' : '' }}>
                                                            {{ $in->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('instansi_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="employment_grade">{{ __('Employment Grade') }} <span
                                                        class="text-danger">*</span></label>
                                                <select name="employment_grade" class="form-control select2" id="employment_grade">
                                                    @foreach(\App\Enums\EmploymentGrade::cases() as $grade)
                                                        <option value="{{ $grade->value }}"
                                                            {{ old('employment_grade', $selected ?? '') === $grade->value ? 'selected' : '' }}>
                                                            {{ $grade->label() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('employment_grade')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Study') }} <span class="text-danger">*</span></label>
                                            <select name="study_id" class="form-control select2" id="study_id">
                                                <option value="">Pilih Program Studi</option>
                                                @foreach ($studies as $study)
                                                    <option value="{{ $study->id }}">{{ $study->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('study_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-8 row px-0 offset-md-2">
                                            <div class="form-group col-md-4">
                                                <label for="education_level">{{ __('Education Level') }} <span
                                                        class="text-danger">*</span></label>
                                                <select name="education_level" class="form-control select2"
                                                    id="education_level">
                                                    <option value="strata_1">{{ __('Strata I') }}</option>
                                                    <option value="strata_2">{{ __('Strata II') }}</option>
                                                    <option value="strata_3">{{ __('Strata III') }}</option>
                                                    <option value="profesi_ppds">{{ __('Profesi, PPDS (Dokter Spesialis)') }}
                                                    </option>
                                                    <option value="ppds_subspesialis">{{ __('PPDS (Dokter Subspesialis)') }}
                                                    </option>
                                                </select>
                                                @error('education_level')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="formation">{{ __('Formation') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="formation" class="form-control" name="formation"
                                                    value="{{ old('formation') }}">
                                                @error('formation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="year">{{ __('Tahun') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="year" id="year" class="form-control"
                                                    value="{{ old('year', date('Y')) }}">
                                                @error('year')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Description') }} <span class="text-danger">*</span></label>
                                            <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div> --}}
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

@push('js')
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
    </script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $("#title").on("keyup", function(e) {
                    $("#slug").val(convertToSlug($(this).val()));
                })
            });
        })(jQuery);

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
    </script>
@endpush
