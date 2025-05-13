@extends('admin.master_layout')
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
                                <h4>{{ __('Certificate Details') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">{{ __('Instansi') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ $certificate->instansi->name }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Certificate') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ $certificate->name }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Goal') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ $certificate->goal }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Competency') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ $certificate->competency }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Indicator of Success') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ $certificate->indicator_of_success }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Activity Plan') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ $certificate->activity_plan }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Start At') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ \Carbon\Carbon::parse($certificate->start_at)->format('d M Y, H:i:s') }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('End At') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ \Carbon\Carbon::parse($certificate->end_at)->format('d M Y, H:i:s') }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('JP') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ $certificate->jp }}" disabled>
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
