@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Tambah Pengakuan Sertifikat') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{ route('student.pengakuan-sertifikat.store') }}" method="POST"
                        class="instructor__profile-form course-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="competency_development_id">Jalur Pengembangan Kompetensi
                                        <code>*</code></label>
                                    <select name="competency_development_id" class="form-control select2" required>
                                        <option value="">Pilih Jalur Pengembangan Kompetensi</option>
                                        @foreach ($competencies as $competency)
                                            <option
                                                value="{{ $competency->id }}"@if (old('competency_development_id') == $competency->id) selected @endif>
                                                {{ $competency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="title">Nama Pengembangan Kompetensi <code>*</code></label>
                                    <input id="title" name="title" type="text" class="form-control" placeholder=""
                                        value="{{ old('title') }}" required>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="organization">Penyelenggara <code>*</code></label>
                                    <input id="organization" name="organization" type="text" class="form-control"
                                        placeholder="" value="{{ old('organization') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai <code>*</code></label>
                                    <input id="start_date" name="start_date" type="date" class="form-control"
                                        placeholder="" value="{{ old('start_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Selesai <code>*</code></label>
                                    <input id="end_date" name="end_date" type="date" class="form-control" placeholder=""
                                        value="{{ old('end_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="file-upload-field2">
                                <div class="form-group">
                                    <label for="file">Unggah Laporan Pengembangan Kompetensi <code>*</code></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"
                                            onclick="$('#report_file').trigger('click');">
                                            <a data-input="report_file" data-preview="holder">
                                                <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                                            </a>
                                        </span>
                                        <input id="report_file_label" readonly class="form-control" type="text"
                                            name="report_file_label" value=""
                                            onclick="$('#report_file').trigger('click');">
                                        <input id="report_file" name="report_file" class="form-control d-none"
                                            type="file" onchange="$('#report_file_label').val(this.files[0].name)"
                                            accept=".pdf">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-2">
                                <div class="form-group">
                                    <label for="certificate_number">Nomor Sertifikat <code>*</code></label>
                                    <input id="certificate_number" name="certificate_number" type="text"
                                        class="form-control" placeholder="" value="{{ old('certificate_number') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4 mt-2">
                                <div class="form-group">
                                    <label for="certificate_date">Tanggal Sertifikat <code>*</code></label>
                                    <input id="certificate_date" name="certificate_date" type="date" class="form-control"
                                        value="{{ old('certificate_date') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 mt-2">
                                <div class="form-group">
                                    <label for="jp">Jumlah Jam Pelajaran <code>*</code></label>
                                    <input id="jp" name="jp" type="number" class="form-control"
                                        placeholder="Jika tidak ada, silakan tulis 0" value="{{ old('jp') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="official_position">Jabatan Pejabat yang mengeluarkan <code>*</code></label>
                                    <input id="official_position" name="official_position" type="text"
                                        class="form-control" placeholder="" value="{{ old('official_position') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="graduation_predicate">Predikat Kelulusan <code>*</code></label>
                                    <input id="graduation_predicate" name="graduation_predicate" type="text"
                                        class="form-control" placeholder="Jika tidak ada, silakan isi dengan tanda -"
                                        value="{{ old('graduation_predicate') }}" required>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="file-upload-field3">
                                <div class="form-group">
                                    <label for="file">Unggah Sertifikat <code>*</code></label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"
                                            onclick="$('#certificate_file').trigger('click');">
                                            <a data-input="certificate_file" data-preview="holder">
                                                <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                                            </a>
                                        </span>
                                        <input id="certificate_file_label" readonly class="form-control" type="text"
                                            name="certificate_file_label" value=""
                                            onclick="$('#certificate_file').trigger('click');">
                                        <input id="certificate_file" name="certificate_file" class="form-control d-none"
                                            type="file" onchange="$('#certificate_file_label').val(this.files[0].name)"
                                            accept=".pdf">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2" id="file-upload-field4">
                                <div class="form-group">
                                    <label for="file">Unggah Piagam Penghargaan / Sejenisnya</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1"
                                            onclick="$('#award_file').trigger('click');">
                                            <a data-input="award_file" data-preview="holder">
                                                <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                                            </a>
                                        </span>
                                        <input id="award_file_label" readonly class="form-control" type="text"
                                            name="award_file_label" value=""
                                            onclick="$('#award_file').trigger('click');">
                                        <input id="award_file" name="award_file" class="form-control d-none"
                                            type="file" onchange="$('#award_file_label').val(this.files[0].name)"
                                            accept=".pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-center">
                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush
