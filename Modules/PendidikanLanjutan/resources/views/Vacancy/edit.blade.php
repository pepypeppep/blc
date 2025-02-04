@extends('admin.master_layout')
@section('title')
    <title>{{ __('Update Vacancy') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Update Vacancy') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.pendidikanlanjutan.index') }}">{{ __('Vacancy List') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Update Vacancy') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Update Vacancy') }}</h4>
                                <div>
                                    <a href="{{ route('admin.pendidikanlanjutan.index') }}" class="btn btn-primary"><i
                                            class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.vacancies.update', $vacancy->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-3">
                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="year">{{ __('Tahun') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="year" id="year" class="form-control"
                                                value="{{ $vacancy->year }}">
                                            @error('year')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Study') }} <span class="text-danger">*</span></label>
                                            <select name="study_id" class="form-control select2" id="study_id">
                                                <option value="">Pilih Program Studi</option>
                                                @foreach ($studies as $study)
                                                    <option value="{{ $study->id }}"
                                                        {{ $study->id == $vacancy->study_id ? 'selected' : '' }}>
                                                        {{ $study->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('study_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="education_level">{{ __('Education Level') }} <span
                                                    class="text-danger">*</span></label>
                                            <select name="education_level" class="form-control select2"
                                                id="education_level">
                                                <option value="strata_1"
                                                    {{ $vacancy->education_level == 'strata_1' ? 'selected' : '' }}>
                                                    {{ __('Strata I') }}</option>
                                                <option value="strata_2"
                                                    {{ $vacancy->education_level == 'strata_2' ? 'selected' : '' }}>
                                                    {{ __('Strata II') }}</option>
                                                <option value="strata_3"
                                                    {{ $vacancy->education_level == 'strata_3' ? 'selected' : '' }}>
                                                    {{ __('Strata III') }}</option>
                                                <option value="profesi_ppds"
                                                    {{ $vacancy->education_level == 'profesi_ppds' ? 'selected' : '' }}>
                                                    {{ __('Profesi, PPDS (Dokter Spesialis)') }}
                                                </option>
                                                <option value="ppds_subspesialis"
                                                    {{ $vacancy->education_level == 'ppds_subspesialis' ? 'selected' : '' }}>
                                                    {{ __('PPDS (Dokter Subspesialis)') }}
                                                </option>
                                            </select>
                                            @error('education_level')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="employment_grade">{{ __('Employment Grade') }} <span
                                                    class="text-danger">*</span></label>
                                            <select name="employment_grade" class="form-control select2"
                                                id="employment_grade">
                                                <option value="juru_ia">{{ __('Juru (Ia)') }}</option>
                                                <option value="juru_muda_tingkat_i_ib">{{ __('Juru Muda Tingkat I (Ib)') }}
                                                </option>
                                                <option value="juru_ic">{{ __('Juru (Ic)') }}</option>
                                                <option value="juru_tingkat_i_id">{{ __('Juru Tingkat I (Id)') }}</option>
                                                <option value="pengatur_iia">{{ __('Pengatur (IIa)') }}</option>
                                                <option value="pengatur_muda_tingkat_i_iib">
                                                    {{ __('Pengatur Muda Tingkat I (IIb)') }}</option>
                                                <option value="pengatur_iic">{{ __('Pengatur (IIc)') }}</option>
                                                <option value="pengatur_tingkat_i_iid">{{ __('Pengatur Tingkat I (IId)') }}
                                                </option>
                                                <option value="penata_iiia">{{ __('Penata (IIIa)') }}</option>
                                                <option value="penata_muda_tingkat_i_iiib">
                                                    {{ __('Penata Muda Tingkat I (IIIb)') }}</option>
                                                <option value="penata_iiic">{{ __('Penata (IIIc)') }}</option>
                                                <option value="penata_tingkat_i_iiid">{{ __('Penata Tingkat I (IIId)') }}
                                                </option>
                                                <option value="pembina_iva">{{ __('Pembina (IVa)') }}</option>
                                                <option value="pembina_tingkat_i_ivb">{{ __('Pembina Tingkat I (IVb)') }}
                                                </option>
                                                <option value="pembina_utama_muda_ivc">{{ __('Pembina Utama Muda (IVc)') }}
                                                </option>
                                                <option value="pembina_utama_madya_ivd">
                                                    {{ __('Pembina Utama Madya (IVd)') }}</option>
                                                <option value="pembina_utama_ive">{{ __('Pembina Utama (IVe)') }}</option>
                                            </select>
                                            @error('employment_grade')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="employment_status">{{ __('Employment Status') }} <span
                                                    class="text-danger">*</span></label>
                                            <select name="employment_status" class="form-control select2"
                                                id="employment_status">
                                                <option value="diberhentikan_dari_jabatan"
                                                    {{ $vacancy->employment_status == 'diberhentikan_dari_jabatan' ? 'selected' : '' }}>
                                                    {{ __('Diberhentikan dari Jabatan') }}</option>
                                                <option value="tidak_diberhentikan_dari_jabatan"
                                                    {{ $vacancy->employment_status == 'tidak_diberhentikan_dari_jabatan' ? 'selected' : '' }}>
                                                    {{ __('Tidak Diberhentikan dari Jabatan') }}</option>
                                            </select>
                                            @error('employment_status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label for="cost_type">{{ __('Cost Type') }} <span
                                                    class="text-danger">*</span></label>
                                            <select name="cost_type" class="form-control select2" id="cost_type">
                                                <option value="apbd"
                                                    {{ $vacancy->cost_type == 'apbd' ? 'selected' : '' }}>
                                                    {{ __('APBD') }}</option>
                                                <option value="non_apbd"
                                                    {{ $vacancy->cost_type == 'non_apbd' ? 'selected' : '' }}>
                                                    {{ __('Non APBD') }}</option>
                                                <option value="mandiri"
                                                    {{ $vacancy->cost_type == 'mandiri' ? 'selected' : '' }}>
                                                    {{ __('Mandiri') }}</option>
                                            </select>
                                            @error('cost_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="col-md-8 row px-0 offset-md-2">
                                            <div class="form-group col-md-6">
                                                <label for="formation">{{ __('Formation') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="formation" class="form-control" name="formation"
                                                    value="{{ $vacancy->formation }}">
                                                @error('formation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>{{ __('Age Limit') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="age_limit" class="form-control"
                                                    name="age_limit" value="{{ $vacancy->age_limit }}">
                                                @error('age_limit')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Description') }} <span class="text-danger">*</span></label>
                                            <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ $vacancy->description }}</textarea>
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
