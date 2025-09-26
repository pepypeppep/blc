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
                            href="{{ route('admin.vacancies.index') }}">{{ __('Vacancy List') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Update Vacancy') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home"
                                    type="button" role="tab" aria-controls="home"
                                    aria-selected="true">{{ __('Update Vacancy') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="details-tab" data-toggle="tab" data-target="#details"
                                    type="button" role="tab" aria-controls="details"
                                    aria-selected="false">{{ __('Detail Vacancy') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="requirement-tab" data-toggle="tab" data-target="#requirement"
                                    type="button" role="tab" aria-controls="requirement"
                                    aria-selected="false">{{ __('Vacancy Attachment') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="invite-tab" data-toggle="tab" data-target="#invite"
                                    type="button" role="tab" aria-controls="invite"
                                    aria-selected="false">{{ __('Direct Invite') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="registrant-tab" data-toggle="tab" data-target="#registrant"
                                    type="button" role="tab" aria-controls="registrant"
                                    aria-selected="false">{{ __('Registrant') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active py-0" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h4>{{ __('Update Vacancy') }}</h4>
                                        <div>
                                            <a href="{{ route('admin.vacancies.index') }}" class="btn btn-primary"><i
                                                    class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.vacancies.update', $vacancy->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row mb-3">
                                                <div class="col-md-8 row px-0 offset-md-2">
                                                    <div class="form-group col-md-8">
                                                        <label>{{ __('Instansi') }} <span
                                                                class="text-danger">*</span></label>
                                                        <select name="instansi_id" class="form-control select2"
                                                            id="instansi_id">
                                                            <option value="">Pilih Instansi</option>
                                                            @foreach ($instansi as $in)
                                                                <option value="{{ $in->id }}"
                                                                    {{ $in->id == $vacancy->instansi_id ? 'selected' : '' }}>
                                                                    {{ $in->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('instansi_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="employee_grade_id">{{ __('Employment Grade') }} <span
                                                                class="text-danger">*</span></label>
                                                        <select name="employee_grade_id" class="form-control select2"
                                                            id="employee_grade_id">
                                                            @foreach ($employeeGrades as $grade)
                                                                <option value="{{ $grade->id }}"
                                                                    {{ $vacancy->employee_grade_id == $grade->id ? 'selected' : '' }}>
                                                                    {{ $grade->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('employee_grade_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-8 row px-0 offset-md-2">
                                                    <div class="form-group col-md-8">
                                                        <label>{{ __('Study') }} <span
                                                                class="text-danger">*</span></label>
                                                        <select name="study_id" class="form-control select2"
                                                            id="study_id">
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
                                                    <div class="form-group col-md-4">
                                                        <label for="education_level">{{ __('Education Level') }} <span
                                                                class="text-danger">*</span></label>
                                                        <select name="education_level" class="form-control select2"
                                                            id="education_level">
                                                            <option value="S-1"
                                                                {{ $vacancy->education_level == 'S-1' ? 'selected' : '' }}>
                                                                {{ __('Strata I') }}</option>
                                                            <option value="S-2"
                                                                {{ $vacancy->education_level == 'S-2' ? 'selected' : '' }}>
                                                                {{ __('Strata II') }}</option>
                                                            <option value="S-3"
                                                                {{ $vacancy->education_level == 'S-3' ? 'selected' : '' }}>
                                                                {{ __('Strata III') }}</option>
                                                            <option value="Profesi PPDS"
                                                                {{ $vacancy->education_level == 'Profesi PPDS' ? 'selected' : '' }}>
                                                                {{ __('Profesi, PPDS (Dokter Spesialis)') }}
                                                            </option>
                                                            <option value="PPDS Subspesialis"
                                                                {{ $vacancy->education_level == 'PPDS Subspesialis' ? 'selected' : '' }}>
                                                                {{ __('PPDS (Dokter Subspesialis)') }}
                                                            </option>
                                                        </select>
                                                        @error('education_level')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-8 row px-0 offset-md-2">
                                                    <div class="form-group col-md-4">
                                                        <label
                                                            for="education_requirements">{{ __('Education Requirements') }}
                                                            <span class="text-danger">*</span></label>
                                                        <select name="education_requirements[]" multiple="multiple"
                                                            class="form-control select2" id="education_requirements">
                                                            @foreach (['SD', 'SMP', 'SMA', 'D-1', 'D-2', 'D-3', 'D-4', 'S-1', 'S-2', 'S-3'] as $sch)
                                                                <option value="{{ $sch }}"
                                                                    {{ $vacancy->education_requirements && in_array($sch, json_decode($vacancy->education_requirements)) ? 'selected' : '' }}>
                                                                    {{ $sch }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('education_requirements')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="formation">{{ __('Formation') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" id="formation" class="form-control"
                                                            name="formation" value="{{ $vacancy->formation }}">
                                                        @error('formation')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="year">{{ __('Tahun') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" name="year" id="year"
                                                            class="form-control" value="{{ $vacancy->year }}">
                                                        @error('year')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                {{-- <div class="form-group col-md-8 offset-md-2">
                                                    <label>{{ __('Description') }} <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ $vacancy->description }}</textarea>
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
                            <div class="tab-pane fade py-0" id="details" role="tabpanel"
                                aria-labelledby="details-tab">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h4>{{ __('Detail Vacancy') }}</h4>
                                        <div>
                                            <a href="{{ route('admin.vacancies.index') }}" class="btn btn-primary"><i
                                                    class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.vacancies.details.update', $vacancy->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row mb-3" id="vacancy_details">
                                                @foreach ($vacancy->details as $detail)
                                                    <div class="col-md-8 row px-0 offset-md-2">
                                                        <div class="form-group col-md-4">
                                                            <label for="employment_status">{{ __('Employment Status') }}
                                                                <span class="text-danger">*</span></label>
                                                            <select name="employment_status[]"
                                                                class="form-control select2" id="employment_status">
                                                                <option value="Tidak diberhentikan dari Jabatan"
                                                                    {{ $detail->employment_status == 'Tidak diberhentikan dari Jabatan' ? 'selected' : '' }}>
                                                                    Tidak diberhentikan dari Jabatan</option>
                                                                <option value="Diberhentikan dari Jabatan"
                                                                    {{ $detail->employment_status == 'Diberhentikan dari Jabatan' ? 'selected' : '' }}>
                                                                    Diberhentikan dari Jabatan</option>
                                                            </select>
                                                            @error('employment_status')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="cost_type">{{ __('Cost Type') }} <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="cost_type[]" class="form-control select2"
                                                                id="cost_type">
                                                                <option value="APBD"
                                                                    {{ $detail->cost_type == 'APBD' ? 'selected' : '' }}>
                                                                    APBD</option>
                                                                <option value="Non APBD"
                                                                    {{ $detail->cost_type == 'Non APBD' ? 'selected' : '' }}>
                                                                    Non APBD</option>
                                                                <option value="Mandiri"
                                                                    {{ $detail->cost_type == 'Mandiri' ? 'selected' : '' }}>
                                                                    Mandiri</option>
                                                            </select>
                                                            @error('cost_type')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="age_limit">{{ __('Age Limit') }} <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="number" name="age_limit[]" id="age_limit"
                                                                class="form-control" value="{{ $detail->age_limit }}">
                                                            @error('age_limit')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-1 d-flex flex-column justify-content-center"
                                                            onclick="removeDetail(this)">
                                                            <button type="button" class="btn btn-danger mx-auto"
                                                                title="Hapus Detail">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="row">
                                                <div class="text-center col-md-8 offset-md-2">
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="addDetail()">Tambah Detail</button>
                                                    <x-admin.save-button :text="__('Save')"></x-admin.save-button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade py-0" id="requirement" role="tabpanel"
                                aria-labelledby="requirement-tab">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h4>{{ __('Vacancy Attachment') }}</h4>
                                        <div>
                                            <a href="{{ route('admin.vacancies.index') }}" class="btn btn-primary"><i
                                                    class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.vacancies.update-attachment', $vacancy->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="row mb-3">
                                                <div class="col-md-12 table">
                                                    <center>
                                                        <table class="table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Lampiran</th>
                                                                    <th>Kategori</th>
                                                                    <th>Wajib <br> <small class="text-danger">(Tandai jika
                                                                            lampiran
                                                                            wajib)</small></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($attachments as $att)
                                                                    @php
                                                                        $atm = \Modules\PendidikanLanjutan\app\Models\VacancyAttachment::where(
                                                                            'vacancy_id',
                                                                            $vacancy->id,
                                                                        )
                                                                            ->where('name', $att->name)
                                                                            ->first();
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check">
                                                                                <input
                                                                                    class="form-check-input cursor-pointer"
                                                                                    id="attachment{{ $att->id }}"
                                                                                    type="checkbox"
                                                                                    name="attachments[{{ $att->id }}]"
                                                                                    value="{{ $att->id }}"
                                                                                    {{ \Modules\PendidikanLanjutan\app\Models\VacancyAttachment::where('vacancy_id', $vacancy->id)->where('name', $att->name)->exists() ? 'checked' : '' }}
                                                                                    onchange="if (!this.checked) {
    $(this).closest('tr').find('#is_required{{ $att->id }}').prop('checked', false).prop('disabled', true);
    $(this).closest('tr').find('#category{{ $att->id }}').prop('required', false).prop('disabled', true);
} else {
    $(this).closest('tr').find('#is_required{{ $att->id }}').prop('disabled', false);
    $(this).closest('tr').find('#category{{ $att->id }}').prop('required', true).prop('disabled', false);
}">
                                                                                <label
                                                                                    class="form-check-label cursor-pointer"
                                                                                    for="attachment{{ $att->id }}">
                                                                                    {{ $att->name }}
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            @if ($att->category == 'syarat')
                                                                                <span
                                                                                    class="badge badge-info">Syarat</span>
                                                                            @elseif ($att->category == 'lampiran')
                                                                                <span
                                                                                    class="badge badge-primary">Lampiran</span>
                                                                            @elseif ($att->category == 'aktivasi')
                                                                                <span
                                                                                    class="badge badge-warning">Aktivasi</span>
                                                                            @endif
                                                                            {{-- <select name="category[{{ $att->id }}]"
                                                                                class="form-control select2"
                                                                                id="category{{ $att->id }}">
                                                                                <option value="">Pilih Kategori
                                                                                </option>
                                                                                <option value="syarat"
                                                                                    {{ $atm?->category == 'syarat' ? 'selected' : '' }}>
                                                                                    Syarat</option>
                                                                                <option value="lampiran"
                                                                                    {{ $atm?->category == 'lampiran' ? 'selected' : '' }}>
                                                                                    Lampiran</option>
                                                                                <option value="syarat"
                                                                                    {{ $atm?->category == 'aktivasi' ? 'selected' : '' }}>
                                                                                    Aktivasi</option>
                                                                            </select> --}}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <div class="form-check">
                                                                                <input type="hidden"
                                                                                    name="is_required[{{ $att->id }}]"
                                                                                    value="0">
                                                                                <input
                                                                                    class="form-check-input cursor-pointer"
                                                                                    id="is_required{{ $att->id }}"
                                                                                    type="checkbox"
                                                                                    name="is_required[{{ $att->id }}]"
                                                                                    value="1"
                                                                                    @if ($atm) @if ($atm->is_required)
                                                                                        checked @endif
                                                                                @else disabled @endif
                                                                                >
                                                                                <label class="form-check-label"></label>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </center>
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
                            <div class="tab-pane fade py-0" id="invite" role="tabpanel" aria-labelledby="invite-tab">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h4>{{ __('Direct Invite') }}</h4>
                                        <div>
                                            <a href="{{ route('admin.vacancies.index') }}" class="btn btn-primary"><i
                                                    class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.vacancies.update-attachment', $vacancy->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="row mb-3">
                                                <div class="col-md-12 table">
                                                    <center>
                                                        <table class="table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Nama</th>
                                                                    <th>Instansi</th>
                                                                    <th>Jabatan</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($directMembers as $member)
                                                                    <tr>
                                                                        <td>{{ $member->user->name }}</td>
                                                                        <td>{{ optional($member->user->instansi)->name ?? '-' }}
                                                                        </td>
                                                                        <td>{{ $member->user->jabatan ?? '-' }}</td>
                                                                    </tr>
                                                                @empty
                                                                    <tr class="text-center">
                                                                        <td colspan="3">
                                                                            {{ __('No Data') }}
                                                                        </td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </center>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="text-center col-md-8 offset-md-2">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#addUserModal">{{ __('Add Member') }}</button>
                                                    <x-admin.save-button :text="__('Save')"></x-admin.save-button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade py-0" id="registrant" role="tabpanel"
                                aria-labelledby="invite-tab">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h4>{{ __('Registrant') }}</h4>
                                        <div>
                                            <a href="{{ route('admin.vacancies.index') }}" class="btn btn-primary"><i
                                                    class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-12 table">
                                                <center>
                                                    <table class="table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama</th>
                                                                <th>Instansi</th>
                                                                <th>Jabatan</th>
                                                                <th>Status</th>
                                                                <th>Jenis</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($members as $member)
                                                                <tr>
                                                                    <td>{{ $member->user->name }}</td>
                                                                    <td>{{ optional($member->user->instansi)->name ?? '-' }}
                                                                    </td>
                                                                    <td>{{ $member->user->jabatan ?? '-' }}</td>
                                                                    <td>{{ $member->status }}</td>
                                                                    <td>{{ \Modules\PendidikanLanjutan\app\Models\VacancyUserDirect::where('vacancy_id', $vacancy->id)->where('user_id', $member->id)->first() ? 'Undangan' : 'Non-Undangan' }}
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr class="text-center">
                                                                    <td colspan="5">
                                                                        {{ __('No Data') }}
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-4" id="addUserModalLabel" style="color:#6777ef;">
                            {{ __('Add Member') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.vacancies.direct.invite', $vacancy->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="selectUser">{{ __('Select Member') }}</label>
                                <select name="users[]" id="selectUser" class="form-control select2" multiple="multiple">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

        function removeDetail(div) {
            $(div).parent().remove();
        }

        function addDetail() {
            let html = `<div class="col-md-8 row px-0 offset-md-2">
                            <div class="form-group col-md-4">
                                <label for="employment_status">{{ __('Employment Status') }} <span
                                        class="text-danger">*</span></label>
                                <select name="employment_status[]" class="form-control select2"
                                    id="employment_status">
                                    <option value="Tidak diberhentikan dari Jabatan">Tidak diberhentikan dari Jabatan</option>
                                    <option value="Diberhentikan dari Jabatan">Diberhentikan dari Jabatan</option>
                                </select>
                                @error('employment_status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cost_type">{{ __('Cost Type') }} <span
                                        class="text-danger">*</span></label>
                                <select name="cost_type[]" class="form-control select2"
                                    id="cost_type">
                                    <option value="APBD">APBD</option>
                                    <option value="Non APBD">Non APBD</option>
                                    <option value="Mandiri">Mandiri</option>
                                </select>
                                @error('cost_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="age_limit">{{ __('Age Limit') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="age_limit[]" id="age_limit" class="form-control"
                                    value="">
                                @error('age_limit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-1 d-flex flex-column justify-content-center" onclick="removeDetail(this)">
                                <button type="button" class="btn btn-danger mx-auto" title="Hapus Detail">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>`;
            $('#vacancy_details').append(html);
        }
    </script>
@endpush
