@extends('admin.master_layout')
@section('title')
    <title>{{ __('Mentoring Detail') }}</title>
@endsection

@php
    use Modules\Mentoring\app\Models\Mentoring;
@endphp
@section('admin-content')


    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Detail Tema Mentoring') }}</h1>
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
                                <h4>{{ $mentoring->title }}</h4>
                                <div class="badge badge-{{ $mentoring->stat['color'] }}">{{ $mentoring->stat['label'] }}</div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Main Issue') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{{ $mentoring->description }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Purpose') }}</p>
                                            <p class="mb-0" style="font-size: 1.1rem;">{{ $mentoring->purpose }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Mentor') }}</p>
                                            <div class="render-content">{{ $mentoring->mentor->name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Session Datetime') }}</p>
                                            <div class="render-content">
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <div>
                                            <p class="text-primary mb-1" style="font-size: 0.9rem; font-weight: 600;">
                                                {{ __('Final Report Mentoring') }}</p>
                                            <div class="render-content">Laporan akhir dari kegiatan mentoring yang telah dilakukan.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('List Mentee') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">{{ __('Front Image') }}</label>
                                    <img src="{{ route('admin.certificate-builder.getBg', 1) }}"
                                        alt="" style="width: 50%; height: auto;"
                                        onerror="this.onerror=null; this.src='{{ asset('assets/img/no-image.png') }}'">
                                </div>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Mentor Availability Letter') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group d-flex justify-content-center">
                                    <a href="#" target="_blank" class="btn btn-outline-danger d-flex align-items-center gap-2" style="font-size: 1.5rem;">
                                        <i class="fas fa-file-pdf fa-2x"></i> <!-- ikon PDF besar -->
                                        <span>Download PDF</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                               <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ __('Certificate Status') }}</h4>

                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="left" title="Tambah Certificate">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="form-group d-flex justify-content-center">
                                        <div id="certificateBg"></div>
                                        <input type="hidden" name="certificate"
                                        value="" class="form-control">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary mt-3"
                                        data-toggle="modal"
                                        data-target="#certificateModal">{{ __('Choose Certificate') }} &nbsp;
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="#6ac88e" stroke="#ffffff" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-badge-check-icon lucide-badge-check">
                                                <path
                                                    d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                                                <path d="m9 12 2 2 4-4" />
                                            </svg></button>
                                    </div>
                                    {{-- <div>
                                        <div id="certificateBg"></div>
                                        <input type="hidden" name="certificate"
                                        value="" class="form-control">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary mt-3"
                                        data-toggle="modal"
                                        data-target="#certificateModal">{{ __('Choose Certificate') }}</button>
                                    </div> --}}
                                    {{-- <div class="row">
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
                                    </div> --}}
                                    <div class="modal fade" id="certificateModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                                                    <h5 class="modal-title" id="certificateModalLabel">
                                                        {{ __('Choose Certificate') }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body row">
                                                    @foreach ($certificates as $certificate)
                                                        <div class="col-md-3 d-flex flex-column">
                                                            <img src="{{ route('admin.certificate-builder.getBg', $certificate->id) }}" alt=""
                                                                style="width: 100%; height: auto;">
                                                            <button class="btn btn-primary mt-auto"
                                                                onclick="chooseCertificate({{ $certificate->id }})">{{ __('Choose') }}</button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{-- <div class="col-lg-6">
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
                    </div> --}}
                </div>
            </div>
        </section>
    </div>

@endsection
{{-- @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush --}}

@push('js')
 <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".datetimepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                altInput: true,
                altFormat: "l, d F Y - H:i",
                locale: "id"
            });
        });

        function handleTolakMentoring(event, id) {
            event.preventDefault();
            swal.fire({
                title: 'Apakah Anda yakin ingin menolak mentoring ini?',
                text: "Silakan berikan alasan penolakan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Totak!',
                input: 'textarea',
                inputPlaceholder: 'Tulis alasan penolakan',
                inputValidator: (value) => {
                    if (!value || value.trim() === "") {
                        return 'Alasan penolakan wajib diisi!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const notes = result.value || '';
                    $.ajax({
                        method: "post",
                        url: base_url + "/student/mentor/" + id + "/reject",
                        data: {
                            _token: csrf_token,
                            reason: notes
                        },
                        success: function(data) {
                            if (data.status === "success") {
                                toastr.success(data.message);
                                window.location.href = base_url + "/student/mentor";
                            }
                        },
                        error: function(xhr, status, error) {
                            const message = xhr.responseJSON?.message || '-';
                            toastr.error(message);
                        },
                    });
                } else {
                    swal.fire('Penolakan dibatalkan', '', 'error');
                }
            })
        };

        function handleApproveMentoring(event) {
            event.preventDefault();
            swal.fire({
                title: 'Apakah Anda yakin ingin memproses mentoring ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#approve_mentoring').submit();
                }
            })
        }
    </script>

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
@endpush

@push('css')
    <style>
    </style>
@endpush
