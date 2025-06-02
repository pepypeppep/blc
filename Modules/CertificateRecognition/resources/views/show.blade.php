@extends('admin.master_layout')
@php
    use Modules\CertificateRecognition\app\Models\CertificateRecognition;
@endphp
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
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Rincian Diklat') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Instansi') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{{ $certificate->instansi->name }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Certificate') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{{ $certificate->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Goal') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{{ $certificate->goal }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Competency') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{{ $certificate->competency }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Indicator of Success') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">
                                                {{ $certificate->indicator_of_success }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Activity Plan') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{{ $certificate->activity_plan }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Start At') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">
                                                {{ \Carbon\Carbon::parse($certificate->start_at)->format('d M Y, H:i:s') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('End At') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">
                                                {{ \Carbon\Carbon::parse($certificate->end_at)->format('d M Y, H:i:s') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('JP') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{{ $certificate->jp }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Certificate Image') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">{{ __('Front Image') }}</label>
                                    <img src="{{ route('admin.certificate-builder.getBg', $certificate->id) }}"
                                        alt="" style="width: 50%; height: auto;"
                                        onerror="this.onerror=null; this.src='{{ asset('assets/img/no-image.png') }}'">
                                </div>
                                @if ($certificate->certificate->background2)
                                    <div class="form-group">
                                        <label for="">{{ __('Back Image') }} <code>( 1123px * 794px )
                                                *</code></label>
                                        <div id="image-preview-background" class="image-preview">
                                            <label for="image-upload-background"
                                                id="image-label-background">{{ __('Image') }}</label>
                                            <input type="file" name="background" id="image-upload-background">
                                        </div>
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Tautan Google Drive') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">{{ __('Link') }}</label>
                                    <input type="text" name="documentation_link" class="form-control"
                                        value="{{ $certificate->documentation_link }}" disabled>
                                </div>
                            </div>
                        </div>
                        @if ($certificate->status != CertificateRecognition::STATUS_PUBLISHED)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>{{ __('Certificate Verification') }}</h4>
                                </div>
                                <div class="card-body">
                                    <form id="verification-form" method="POST"
                                        action="{{ route('admin.certificate-recognition.verify', $certificate->id) }}">
                                        @method('PUT')
                                        @csrf
                                        <input type="hidden" name="status" id="verification-status">
                                        <input type="hidden" name="reason" id="verification-reason">
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-primary hover-bg-primary"
                                                style="width: 48%; border: 2px solid #6777ef;"
                                                onclick="handleApprove(event, 'verification-form')">
                                                <i class="fa fa-check"></i>
                                                {{ __('Approved') }}
                                            </button>
                                            <button type="button" class="btn btn-outline-danger hover-bg-danger"
                                                style="width: 48%; border: 2px solid #fc544b;"
                                                onclick="handleReject(event, 'verification-form')">
                                                <i class="fa fa-times"></i>
                                                {{ __('Rejected') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                        @if ($certificate->status == CertificateRecognition::STATUS_PUBLISHED)
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>{{ __('Certificate Status') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 d-flex align-items-center">
                                            <h6 class="text-center mb-0">{{ __('Published') }}</h6>
                                            &nbsp;
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="#6ac88e" stroke="#ffffff" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-badge-check-icon lucide-badge-check">
                                                <path
                                                    d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                                                <path d="m9 12 2 2 4-4" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Daftar Peserta') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class=" max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:1%">No</th>
                                                <th style="width:35%">{{ __('Name') }}</th>
                                                <th style="width:35%">{{ __('Jabatan') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $user)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="row">
                                                        <div class="col-12">
                                                            <strong>{{ $user->name }}</strong>
                                                        </div>
                                                        <div class="col-12">
                                                            <span>Status ASN :</span>
                                                            @if ($user->asn_status == 'PNS')
                                                                <span class="badge badge-primary">PNS</span>
                                                            @elseif ($user->asn_status == 'PPPK')
                                                                <span class="badge badge-success">PPPK</span>
                                                            @else
                                                                <span class="badge badge-warning">Lainnya</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->jabatan }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">{{ __('No data found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $users->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Daftar Materi Pelatihan') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class=" max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:1%">No</th>
                                                <th style="width:35%">{{ __('Name') }}</th>
                                                <th style="width:35%">{{ __('JP') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pelatihans as $pelatihan)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $pelatihan->name }}</td>
                                                    <td>{{ $pelatihan->jp }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">{{ __('No data found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $pelatihans->links() }}
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
        function handleApprove(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: '{{ __('Do you want to approve this certificate?') }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('Yes, approve it!') }}',
                cancelButtonText: '{{ __('Cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#verification-status').val('approved');
                    $('#verification-form').submit();
                }
            });
        }

        function handleReject(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: '{{ __('Do you want to reject this certificate?') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('Yes, reject it!') }}',
                cancelButtonText: '{{ __('Cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '{{ __('Explain the reason for rejection...') }}',
                        input: 'text',
                        inputPlaceholder: '{{ __('Explain the reason for rejection...') }}',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __('Submit') }}',
                        cancelButtonText: '{{ __('Cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#verification-status').val('rejected');
                            $('#verification-reason').val(result.value);
                            $('#verification-form').submit();
                        }
                    });
                }
            });
        }
    </script>
@endpush
