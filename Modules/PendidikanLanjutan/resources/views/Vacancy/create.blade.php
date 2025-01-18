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
                    <div class="breadcrumb-item active"><a href="{{ route('admin.pendidikanlanjutan.index') }}">{{ __('Vacancy List') }}</a>
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
                                    <a href="{{ route('admin.pendidikanlanjutan.index') }}" class="btn btn-primary"><i
                                            class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.blogs.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Unit Organisasi') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="unor" class="form-control" name="unor"
                                                value="{{ old('unor') }}">
                                            @error('unor')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="education_level">{{ __('Jenjang Pendidikan Lanjutan') }} <span class="text-danger">*</span></label>
                                            <select name="education_level" class="form-control select2" id="education_level">
                                                <option value="strata_1">{{ __('Strata I') }}</option>
                                                <option value="strata_2">{{ __('Strata II') }}</option>
                                                <option value="strata_3">{{ __('Strata III') }}</option>
                                                <option value="profesi_ppds">{{ __('Profesi, PPDS (Dokter Spesialis)') }}</option>
                                                <option value="ppds_subspesialis">{{ __('PPDS (Dokter Subspesialis)') }}</option>
                                            </select>
                                            @error('education_level')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Program Studi Pendidikan Lanjutan') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="study_program" class="form-control" name="study_program"
                                                value="{{ old('study_program') }}">
                                            @error('study_program')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="minimum_rank">{{ __('Syarat Minimal Pangkat/Golongan') }} <span class="text-danger">*</span></label>
                                            <select name="minimum_rank" class="form-control select2" id="minimum_rank">
                                                <option value="juru_ia">{{ __('Juru (Ia)') }}</option>
                                                <option value="juru_muda_tingkat_i_ib">{{ __('Juru Muda Tingkat I (Ib)') }}</option>
                                                <option value="juru_ic">{{ __('Juru (Ic)') }}</option>
                                                <option value="juru_tingkat_i_id">{{ __('Juru Tingkat I (Id)') }}</option>
                                                <option value="pengatur_iia">{{ __('Pengatur (IIa)') }}</option>
                                                <option value="pengatur_muda_tingkat_i_iib">{{ __('Pengatur Muda Tingkat I (IIb)') }}</option>
                                                <option value="pengatur_iic">{{ __('Pengatur (IIc)') }}</option>
                                                <option value="pengatur_tingkat_i_iid">{{ __('Pengatur Tingkat I (IId)') }}</option>
                                                <option value="penata_iiia">{{ __('Penata (IIIa)') }}</option>
                                                <option value="penata_muda_tingkat_i_iiib">{{ __('Penata Muda Tingkat I (IIIb)') }}</option>
                                                <option value="penata_iiic">{{ __('Penata (IIIc)') }}</option>
                                                <option value="penata_tingkat_i_iiid">{{ __('Penata Tingkat I (IIId)') }}</option>
                                                <option value="pembina_iva">{{ __('Pembina (IVa)') }}</option>
                                                <option value="pembina_tingkat_i_ivb">{{ __('Pembina Tingkat I (IVb)') }}</option>
                                                <option value="pembina_utama_muda_ivc">{{ __('Pembina Utama Muda (IVc)') }}</option>
                                                <option value="pembina_utama_madya_ivd">{{ __('Pembina Utama Madya (IVd)') }}</option>
                                                <option value="pembina_utama_ive">{{ __('Pembina Utama (IVe)') }}</option>
                                            </select>
                                            @error('minimum_rank')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="employment_status">{{ __('Status Kepegawaian') }} <span class="text-danger">*</span></label>
                                            <select name="employment_status" class="form-control select2" id="employment_status">
                                                <option value="diberhentikan_dari_jabatan">{{ __('Diberhentikan dari Jabatan') }}</option>
                                                <option value="tidak_diberhentikan_dari_jabatan">{{ __('Tidak Diberhentikan dari Jabatan') }}</option>
                                            </select>
                                            @error('employment_status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="funding_source">{{ __('Pembiayaan') }} <span class="text-danger">*</span></label>
                                            <select name="funding_source" class="form-control select2" id="funding_source">
                                                <option value="non_apbd">{{ __('Non APBD') }}</option>
                                                <option value="mandiri">{{ __('Mandiri') }}</option>
                                            </select>
                                            @error('funding_source')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="formasi_count">{{ __('Jumlah Formasi') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="formasi_count" class="form-control" name="formasi_count"
                                                value="{{ old('formasi_count') }}">
                                            @error('formasi_count')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Batas Usia Pensiun') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="retirement_age" class="form-control"
                                                name="retirement_age" value="{{ old('retirement_age') }}">
                                            @error('retirement_age')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="form-group col-md-8 offset-md-2">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>{{ __('Start Date') }} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" id="start_at" class="form-control"
                                                        name="start_at" value="{{ old('start_at') }}">
                                                    @error('start_at')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>{{ __('End Date') }} <span class="text-danger">*</span></label>
                                                    <input type="date" id="end_at" class="form-control" name="end_at"
                                                        value="{{ old('end_at') }}">
                                                    @error('end_at')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Description') }} <span class="text-danger">*</span></label>
                                            <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
