@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Certificate of Recognition') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary">{{ __('Create Certificate of Recognition') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Create Certificate of Recognition') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="dashboard__content-wrap">
                    <div class="row">
                        <div class="col-12">
                            @if(old('is_approved') == 'rejected' && old('notes'))
                                <div class="alert alert-danger">
                                    <strong>Rejected:</strong> {{ old('notes') }}
                                </div>
                            @endif

                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('admin.certificate-recognition.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label>{{ __('Instansi') }} <code>*</code></label>
                                                    <select name="instansi_id" class="form-control select2" required>
                                                        <option value="">{{ __('Select') }}</option>
                                                        @foreach($instansis as $instansi)
                                                            <option value="{{ $instansi->id }}" {{ old('instansi_id') == $instansi->id ? 'selected' : '' }}>
                                                                {{ $instansi->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label>{{ __('Course Name') }} <code>*</code></label>
                                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label for="from_date_start">Pelaksanaan Pembelajaran <code>*</code></label>
                                                    <div class="input-group input-daterange">
                                                        <input type="text" name="start_at" class="form-control" value="{{ old('start_at') }}">
                                                        <span class="input-group-text mx-2">{{ __('Sampai') }}</span>
                                                        <input type="text" name="end_at" class="form-control" value="{{ old('end_at') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div class="form-group">
                                                    <label>{{ __('Status') }}</label>
                                                    <select name="status" class="form-control">
                                                        <option value="is_draft" {{ old('status') == 'is_draft' ? 'selected' : '' }}>Draft</option>
                                                        <option value="verification" {{ old('status') == 'verification' ? 'selected' : '' }}>Ajukan</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <div class="form-group">
                                                    <label>{{ __('Documentation Link') }}</label>
                                                    <input type="text" name="documentation_link" class="form-control" value="{{ old('documentation_link') }}" placeholder="https://drive.google.com/file/d/1qZzL3qHq4q4q4q4q4q4q4/view?usp=sharing">
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label>{{ __('Goal') }}</label>
                                                    <textarea name="goal" class="form-control summernote">{{ old('goal') }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label>{{ __('Competency') }}</label>
                                                    <textarea name="competency" class="form-control summernote">{{ old('competency') }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label>{{ __('Indicator of Success') }}</label>
                                                    <textarea name="indicator_of_success" class="form-control summernote">{{ old('indicator_of_success') }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label>{{ __('Activity Plan') }}</label>
                                                    <textarea name="activity_plan" class="form-control summernote">{{ old('activity_plan') }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label>{{ __('Peserta') }} <code>*</code></label>
                                                    <select name="participants[]" class="form-control select2" multiple required>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}" {{ collect(old('participants'))->contains($user->id) ? 'selected' : '' }}>
                                                                {{ $user->name }} ({{ optional($user->instansi)->name }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Materi Pelatihan') }} <button type="button" class="btn btn-sm btn-primary" title="Tambah Materi" onclick="addTrainingMaterial()"><i class="fa fa-plus"></i></button></th>
                                                        <th>{{ __('JP (Jam Pelajaran)') }}</th>
                                                        <th>Aksi</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="training-material">
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="materi[]" class="form-control" placeholder="Materi Pelatihan">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="jp[]" class="form-control" placeholder="0">
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 d-flex justify-content-center">
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
                .replace(/[^a-z\s-]/g, "")
                .replace(/\s+/g, "-")
                .replace(/-+/g, "-");
        }

        $('.input-daterange').datepicker({
            todayBtn: "linked",
            clearBtn: true,
            autoclose: false,
            format: "yyyy-mm-dd"
        });

    });

    function chooseCertificate(id) {
        $('#certificateModal').modal('hide');
        $('input[name="certificate"]').val(id);
        $('#certificateBg').html(
            '<img src="{{ route('admin.certificate-builder.getBg', ':id') }}" alt="" style="width: 100%; height: auto;" />'
            .replace(':id', id)
        );
    }

    function addTrainingMaterial() {
        const html = `<tr>
                        <td>
                            <input type="text" name="materi[]" class="form-control" placeholder="Materi Pelatihan" value="{{ old('materi[]') }}">
                        </td>
                        <td>
                            <input type="number" name="jp[]" class="form-control" placeholder="0" value="{{ old('jp[]') }}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger" onclick="removeTrainingMaterial(this)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;

        $('#training-material').append(html);
    }

    function removeTrainingMaterial(el) {
        $(el).closest('tr').remove();
    }
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
