@extends('admin.master_layout')
@section('title')
    <title>{{ __('Registrant Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Registrant Details') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('admin.verification.index') }}">{{ __('Verification') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Registrant Details') }}</div>
                </div>
            </div>

            <div class="section-header-menu">
                <a href="#detail" class="btn btn-link " role="button">
                    <i class="fa fa-user" style="font-size: 18px;"></i> <br/> Detail
                </a>
                <span class="separator"> > </span> 
                <a href="#verif" class="btn btn-link {{ $vacancyUser->status === 'verification' ? '' : 'disabled' }}" role="button">
                    <i class="fa fa-clipboard-check" style="font-size: 18px;"></i>  <br/> Verifikasi
                </a>
                <span class="separator"> > </span> 
                <a href="#verif" class="btn btn-link {{ $vacancyUser->status === 'assesment' ? '' : 'disabled' }}" role="button">
                    <i class="fa fa-tasks" style="font-size: 18px;"></i>  <br/> Penilaian
                </a>
                <span class="separator"> > </span> 
                <a href="#ptb" class="btn btn-link {{ $vacancyUser->status !== 'verification' ? 'disabled' : '' }}" role="button">
                    <i class="fa fa-file-contract" style="font-size: 18px;"></i> <br/> Perjanjian Tubel
                </a>
                <span class="separator"> > </span> 
                <a href="#sk" class="btn btn-link {{ $vacancyUser->status !== 'passed' ? 'disabled' : '' }}" role="button">
                    <i class="fa fa-file-alt" style="font-size: 18px;"></i> <br/> Surat Keputusan
                </a>
                <span class="separator"> > </span> 
                <a href="#laporan" class="btn btn-link {{ $vacancyUser->status !== 'passed' ? 'disabled' : '' }}" role="button">
                    <i class="fa fa-file-invoice" style="font-size: 18px;"></i>  <br/> Laporan
                </a>
                <span class="separator"> > </span> 
                <a href="#perpanjangan" class="btn btn-link {{ $vacancyUser->status !== 'passed' ? 'disabled' : '' }}" role="button">
                    <i class="fa fa-calendar-check" style="font-size: 18px;"></i>  <br/> Perpanjangan
                </a>
            </div>

            <div class="section-body">
                <div id="detail" class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">{{ __('Registrant Details') }}</div>
                                <hr>
                                <div class="registrant-info-header">{{ __('Registrant Information') }}</div>
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Employee Id') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->user->nip }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Date and Place of Birth') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : -
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Employee Name') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->user->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Cost Type') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : -
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Employment Grade') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : {{ $vacancyUser->vacancy->employment_grade }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Last Education Level') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : -
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Last Position') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : -
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Phone Number') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : -
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{ __('Employment Unit') }}
                                                </div>
                                                <div class="col-md-6">
                                                    : -
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <div class="registrant-info-header">{{ __('Requirements Document') }}</div>
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>{{ __('File') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    @foreach ($vacancyUserAttachments as $attachment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $attachment->vacancyAttachment->name }}</td>
                                            <td>{{ $attachment->category }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm m-1" data-toggle="modal" data-target="#pdfModal" title="Lihat Berkas" onclick="setPDF('{{ asset('storage/'.$attachment->file) }}')">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($vacancyUser->status === 'verification')
            <div class="section-body">
                <div id="verif" class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">{{ __('Verification') }}</div>
                                <hr>
                                <div class="col-md-12 mb-2">
                                    <label>{{ __('Reason') }} <span class="text-danger">*</span></label>
                                    <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-3 text-md-center">
                                    <a target="_blank" href="" class="btn btn-danger btn-icon icon-left print-btn"><i class="fas fa-times"></i>
                                        {{ __('Reject') }}</a>
                                    <a target="_blank" href="" class="btn btn-primary btn-icon icon-left print-btn"><i class="fas fa-check"></i>
                                        {{ __('Verification') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($vacancyUser->status === 'assesment')
            <div class="section-body">
                <div id="assesment" class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">{{ __('Assesment') }}</div>
                                <hr>
                                <div class="col-md-12 mb-2">
                                    <label>{{ __('Reason') }} <span class="text-danger">*</span></label>
                                    <textarea name="description" id="" cols="30" rows="10" class="summernote">{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mt-3 text-md-center">
                                    <a target="_blank" href="" class="btn btn-danger btn-icon icon-left print-btn"><i class="fas fa-times"></i>
                                        {{ __('Reject') }}</a>
                                    <a target="_blank" href="" class="btn btn-primary btn-icon icon-left print-btn"><i class="fas fa-check"></i>
                                        {{ __('Approve') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            @if($vacancyUser->status === 'passed')
            <div class="section-body">
                <div id="ptb" class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">
                                    <div class="d-flex justify-content-between">
                                        {{ __('Study Assignment Agreement') }}
                                        <div>
                                            <a href="{{ asset('template/template.pdf') }}" class="btn btn-warning">
                                                <i class="fa fa-download"></i> {{ __('Download PK') }}
                                            </a>
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
                                                <i class="fa fa-upload"></i> {{ __('Upload PDF') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div id="uploaded-file-info" class="mt-3" style="display: block;">
                                    <strong>{{ __('Uploaded File:') }}</strong>
                                    <span id="file-name">test.pdf</span>
                                    <br>
                                    <embed id="pdf-preview-embed" src="{{ asset('template/template.pdf') }}" type="application/pdf" width="100%" height="400px" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div id="sk" class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">
                                    <div class="d-flex justify-content-between">
                                        {{ __('Draft SK') }}
                                        <div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>{{ __('File') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    @foreach ($vacancyUserAttachmentSK as $attachment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $attachment->vacancyAttachment->name }}</td>                                            
                                            <td>{{ $attachment->category }}</td>
                                            <td>{{ $attachment->vacancyAttachment->is_active === 1 ? 'true' : 'false' }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm m-1" data-toggle="modal" data-target="#pdfModal" 
                                                    title="Lihat Berkas" onclick="setPDF('{{ asset('storage/'.$attachment->file) }}')">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="laporan" class="section-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">
                                    <div class="d-flex justify-content-between">
                                        {{ __('Report') }}
                                        <div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>{{ __('Semester') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>1</td>
                                        <td>xx</td>
                                        <td>Created</td>
                                        <td>
                                            <button class="btn btn-info btn-sm m-1" data-toggle="modal" data-target="#pdfModal" onclick="setPDF('{{ asset('template/template.pdf') }}')">
                                                <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;{{ __('See') }}
                                            </button>
                                            <a href="javascript:void(0);" class="btn btn-success btn-sm m-1" title="Verifikasi Laporan" id="verifyButton">
                                                <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;{{ __('Verify') }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Final</td>
                                        <td>xx</td>
                                        <td>Approved</td>
                                        <td>
                                            <button class="btn btn-info btn-sm m-1" data-toggle="modal" data-target="#pdfModal" onclick="setPDF('{{ asset('template/template.pdf') }}')">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </button>
                                            <a href=""
                                                class="btn btn-success btn-sm m-1" title="Verifikasi Laporan">
                                                <i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;{{ __('Verify') }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <hr>
                                <div class="col-md-12 mb-2">
                                    <div class="alert alert-primary alert-has-icon alert-dismissible" id="studyCompletionAlert">
                                        <div class="alert-icon"><i class="far fa-check-circle"></i></div>
                                        <div class="alert-body">
                                            <div class="alert-title">Konfirmasi Penyelesaian Tugas Belajar</div>
                                            Pastikan laporan telah lengkap dan syarat lainnya telah dipenuhi oleh peserta sebelum konfirmasi bahwa tugas belajar telah selesai dilakukan.
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 text-md-center">
                                    <a target="_blank" href="" class="btn btn-primary btn-icon icon-left print-btn"><i class="fas fa-check"></i>
                                        {{ __('Approve') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div id="ptb" class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="section-title">
                                    <div class="d-flex justify-content-between">
                                        {{ __('Extension') }}
                                    </div>
                                </div>
                                <hr>
                                <form action="" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="form-group col-md-8 offset-md-2">
                                            <label>{{ __('Reason') }} <span class="text-danger">*</span></label>
                                            <textarea name="extension_reason" id="" cols="30" rows="10" class="summernote">{{ old('extension_reason') }}</textarea>
                                            @error('extension_reason')
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
        @endif

        <!-- Modal for PDF upload -->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title ml-4" id="uploadModalLabel">{{ __('Upload PDF') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.vacancies-participant.upload.file', ['vacancyId' => $vacancyUser->vacancy_id, 'userId' => $vacancyUser->user_id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="pdf_upload">{{ __('Choose PDF to Upload') }}</label>
                                <input type="file" class="form-control" id="pdf_upload" name="pdf_file" accept=".pdf">
                            </div>

                            <div id="pdf-preview" class="mt-3" style="display: none;">
                                <label>{{ __('Preview PDF') }}</label><br>
                                <embed id="pdf-preview-embed" src="" type="application/pdf" width="100%" height="400px" />
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">{{ __('Upload PDF') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for PDF preview -->
        <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title ml-4" id="uploadModalLabel">{{ __('Preview PDF') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <object id="pdfObject" data="" type="application/pdf" width="100%" height="420px"></object>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi -->
        <div class="modal" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title ml-4" id="verifyModalLabel">Verifikasi Laporan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <p></p> -->
                        <div class="mb-3">
                            <label for="verificationMessage" class="form-label">Pesan</label>
                            <textarea class="form-control" id="verificationMessage" rows="4" placeholder="Tuliskan pesan Anda di sini"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="rejectButton">Tolak</button>
                        <button type="button" class="btn btn-success" id="acceptButton">Terima</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .section-header-menu {
            position: sticky;
            top: 0;
            background-color: #8c9bfe;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .section-header-menu a {
            padding: 10px 15px;
            font-size: 16px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
            display: inline-block;
        }

        .section-header-menu a.disabled {
            pointer-events: none;
            color: white !important;
            cursor: not-allowed;
        }

        .section-header-menu a i {
            color: white;
        }

        .section-header-menu a:hover {
            background-color: #0069d9;
            color: #8c9bfe;
            border-radius: 5px;
        }

        .section-header-menu a:hover i {
            color: #8c9bfe;
        }

        .section-header-menu .active {
            font-weight: bold;
            color: #007bff; /* blue color for active link */
        }

        .separator {
            color: white;
            margin: 10px 5px;
            font-size: 14px;
            display: inline-block;
        }

        .registrant-info-header {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('pdf_upload').addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var pdfEmbed = document.getElementById('pdf-preview-embed');
                    pdfEmbed.src = event.target.result;
                    document.getElementById('pdf-preview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script>
        document.getElementById('verifyButton').addEventListener('click', function (e) {
            e.preventDefault();

            var verifyModal = new bootstrap.Modal(document.getElementById('verifyModal'));
            verifyModal.show();
        });

        document.getElementById('acceptButton').addEventListener('click', function () {
            var message = document.getElementById('verificationMessage').value;

            Swal.fire('Berhasil!', 'Laporan berhasil diverifikasi.', 'success');
            var verifyModal = bootstrap.Modal.getInstance(document.getElementById('verifyModal'));
            verifyModal.hide();
        });

        document.getElementById('rejectButton').addEventListener('click', function () {
            var message = document.getElementById('verificationMessage').value;

            if (message.trim() === "") {
                Swal.fire('Perhatian!', 'Pesan harus diisi sebelum menolak laporan.', 'error');
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Penolakan',
                text: 'Anda yakin ingin menolak laporan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Berhasil!', 'Laporan berhasil ditolak.', 'success');
                    var verifyModal = bootstrap.Modal.getInstance(document.getElementById('verifyModal'));
                    verifyModal.hide();
                }
            });
        });
    </script>
    <script>
        function setPDF(pdfUrl) {
            document.getElementById('pdfObject').setAttribute('data', pdfUrl);
        }
    </script>
@endpush