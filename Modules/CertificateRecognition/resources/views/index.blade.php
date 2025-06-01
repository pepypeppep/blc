@extends('admin.master_layout')
@use('Modules\CertificateRecognition\app\Models\CertificateRecognition')
@section('title')
    <title>{{ __('Certificate Recognition') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Certificate Recognition') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Certificate Recognition') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.certificate-recognition.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')" class="form_padding">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <select name="is_approved" id="is_approved" class="form-control">
                                                <option value="">{{ __('Select Status Approval') }}</option>
                                                <option value="approved"
                                                    {{ request('is_approved') == 'approved' ? 'selected' : '' }}>
                                                    {{ __('Active') }}
                                                </option>
                                                <option value="rejected"
                                                    {{ request('is_approved') == 'rejected' ? 'selected' : '' }}>
                                                    {{ __('In-Active') }}
                                                </option>
                                                <option value="pending"
                                                    {{ request('is_approved') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <select name="certificate_status" id="certificate_status" class="form-control">
                                                <option value="">{{ __('Select Certificate Status') }}</option>
                                                <option value="pending"
                                                    {{ request('certificate_status') == 'pending' ? 'selected' : '' }}>
                                                    {{ __('Pending') }}
                                                </option>
                                                <option value="process"
                                                    {{ request('certificate_status') == 'process' ? 'selected' : '' }}>
                                                    {{ __('Process') }}
                                                </option>
                                                <option value="finish"
                                                    {{ request('certificate_status') == 'finish' ? 'selected' : '' }}>
                                                    {{ __('Finish') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Certificate Recognition List') }}</h4>
                                <div>
                                    <div>
                                        <a href="{{ route('admin.certificate-recognition.create') }}"
                                            class="btn btn-primary"> <i class="fa fa-plus"></i>{{ __('Add New') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class=" max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Instansi') }}</th>
                                                <th>{{ __('Course Name') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Approval Status') }}</th>
                                                <th>{{ __('Certificate Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($certificateRecognitions as $index => $certificate)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td><a
                                                            href="{{ route('admin.certificate-recognition.show', $certificate->id) }}">{{ $certificate->name }}</a>
                                                    </td>
                                                    <td>{{ $certificate->instansi->name }}</td>
                                                    <td>{{ $certificate->name }}</td>
                                                    <td>
                                                        <div class="badge badge-{{ $certificate->stat['color'] }}">
                                                            {{ __($certificate->stat['label']) }}</div>
                                                    </td>
                                                    <td>
                                                        <div
                                                            class="badge badge-{{ $certificate->approval_stat['color'] }}">
                                                            {{ __($certificate->approval_stat['label']) }}</div>
                                                    </td>
                                                    <td>
                                                        <div
                                                            class="badge badge-{{ $certificate->certificate_stat['color'] }}">
                                                            {{ __($certificate->certificate_stat['label']) }}</div>
                                                    </td>
                                                    <td class="flex text-end justify-end items-end">
                                                        @if ($certificate->status != CertificateRecognition::STATUS_PUBLISHED)
                                                            @adminCan('sertifikat.pengakuan.verify')
                                                                <a href="{{ route('admin.certificate-recognition.verify', $certificate->id) }}"
                                                                    class="btn btn-primary btn-sm"><i class="fa fa-check"></i>
                                                                </a>
                                                            @endadminCan
                                                        @endif
                                                        @adminCan('sertifikat.pengakuan.view')
                                                            <a href="{{ route('admin.certificate-recognition.show', $certificate->id) }}"
                                                                class="btn btn-info btn-sm"><i class="fa fa-eye"></i>
                                                            </a>
                                                        @endadminCan
                                                        @adminCan('sertifikat.pengakuan.destroy')
                                                            <form
                                                                action="{{ route('admin.certificate-recognition.destroy', $certificate->id) }}"
                                                                method="POST" id="delete-form-{{ $certificate->id }}"
                                                                style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="javascript:void(0)"
                                                                    onclick="handleDelete(event, 'delete-form-{{ $certificate->id }}')"
                                                                    class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>
                                                                </a>
                                                            </form>
                                                        @endadminCan
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Certificate Recognition')" route="" create="no"
                                                    :message="__('No data found!')" colspan="9"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $certificateRecognitions->links() }}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif

    <script>
        function handleDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: '{{ __('Do you want to delete this certificate recognition?') }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '{{ __('Yes') }}',
                cancelButtonText: '{{ __('No') }}',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endpush

@push('css')
    <style>
    </style>
@endpush
