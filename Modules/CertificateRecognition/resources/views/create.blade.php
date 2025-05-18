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
                                    <form action="{{ route('certificaterecognition.store') }}" method="POST" enctype="multipart/form-data" class="course-form">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
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

                                            <div class="col-md-6 mb-3">
                                                <label>{{ __('Name') }} <code>*</code></label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label>{{ __('Goal') }}</label>
                                                <textarea name="goal" class="form-control summernote">{{ old('goal') }}</textarea>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label>{{ __('Competency') }}</label>
                                                <textarea name="competency" class="form-control summernote">{{ old('competency') }}</textarea>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label>{{ __('Indicator of Success') }}</label>
                                                <textarea name="indicator_of_success" class="form-control summernote">{{ old('indicator_of_success') }}</textarea>
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label>{{ __('Activity Plan') }}</label>
                                                <textarea name="activity_plan" class="form-control summernote">{{ old('activity_plan') }}</textarea>
                                            </div>

                                            <div class="col-md-6 my-4">
                                                <div class="form-group">
                                                    <label for="from_date_start">Pelaksanaan Pembelajaran <code>*</code></label>
                                                    <div class="input-group input-daterange">
                                                        <input type="text" name="start_date" class="form-control" value="{{ old('start_date') }}">
                                                        <span class="input-group-text mx-2">{{ __('Sampai') }}</span>
                                                        <input type="text" name="end_date" class="form-control" value="{{ old('end_date') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ __('JP (Jam Pelajaran)') }}</label>
                                                <input type="number" name="jp" class="form-control" value="{{ old('jp') ?? 0 }}">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ __('Status') }}</label>
                                                <select name="status" class="form-control">
                                                    <option value="is_draft" {{ old('status') == 'is_draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>{{ __('Peserta (User IDs)') }}</label>
                                                <input type="text" name="participants[]" class="form-control" placeholder="User ID">
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
