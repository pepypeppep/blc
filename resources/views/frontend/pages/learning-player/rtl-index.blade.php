@extends('frontend.pages.learning-player.master')

@section('contents')
    <section class="wsus__course_video">
        <div class="col-12">
            <div class="wsus__course_header">
                @if (Session::has('course_slug'))
                    <a href="{{ route('student.learning.index', Session::get('course_slug')) }}"><i
                            class="fas fa-angle-left"></i>{{ truncate(Session::get('course_title')) }}</a>
                @endif
            </div>
        </div>

        <div class="py-8">
            <div class="container">
                <!-- Title -->
                <div class="d-flex justify-content-between align-items-center py-3">
                    <h2 class="h5 mb-0">
                        <a href="#" class="text-muted"></a> Tindak Lanjut RTL
                    </h2>
                </div>

                <!-- Main content -->
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Details -->
                        <div class="card mb-4">
                            <div class="card-body">
                                @php
                                    use Carbon\Carbon;
                                    $startDate = Carbon::parse($item->start_date);
                                    $endDate = Carbon::parse($item->due_date);
                                    $now = Carbon::now();
                                    // $response = $item->followUpActionResponse;
                                @endphp

                                @if ($response)
                                    <h5>Ringkasan Rencana Tindak Lanjut</h5>
                                    <div class="my-3">{!! $response->participant_response !!}</div>
                                    <div class="card shadow-sm border-0 mt-4">

                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0 text-white">
                                                <i class="fa fa-file"></i> File Rencana Tindak Lanjut
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <iframe
                                                src="{{ route('student.follow-up-action.files', $response->participant_file) }}"
                                                class="w-100 border" style="height: 600px;"></iframe>
                                        </div>
                                    </div>
                                @endif
                                @if ($now->lt($startDate))
                                    <div class="alert alert-warning mt-4">
                                        <strong>Perhatian:</strong> Belum periode pengisian data untuk Rencana Tindak
                                        Lanjut. Mulai pada
                                        <strong>{{ $startDate->translatedFormat('d F Y') }}</strong>.
                                    </div>
                                @elseif ($now->gt($endDate) && !$response)
                                    <div class="alert alert-danger mt-4">
                                        <strong>Perhatian:</strong> Periode pengisian Rencana Tindak Lanjut akan berakhir
                                        pada
                                        <strong>{{ $endDate->translatedFormat('d F Y') }}</strong>.
                                    </div>
                                @elseif (!$response)
                                    <div class="card shadow-sm border-0 mt-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0 text-white">
                                                <i class="fa-solid fa-square-plus"></i> Masukan Data Rencana Tindak Lanjut
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <form id="contentForm" enctype="multipart/form-data"
                                                action="{{ route('student.rtl.store', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="follow_up_action_id"
                                                    value="{{ $item->id }}">

                                                <div class="form-group">
                                                    <div class="mt-2">
                                                        <label for="fileSurat" class="form-label">Unggah File Rencana Tindak
                                                            Lanjut</label>
                                                        <div class="upload-card">
                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                            <h5 class="mb-3">Pilih berkas atau drag & drop di sini</h5>
                                                            <p class="text-muted">Hanya format PDF, maksimal 30MB</p>
                                                            <input name="file_path" type="file" id="fileInput"
                                                                accept=".pdf">
                                                            <label for="fileInput">Telusuri Berkas</label>
                                                        </div>

                                                        <div id="fileInfo" style="display: none;">
                                                            <div class="file-info">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-file-pdf fa-lg"></i>
                                                                    <span id="fileName" class="fw-bold"></span>
                                                                </div>
                                                                <div class="file-actions">
                                                                    <button type="button" id="previewBtn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#previewModal"
                                                                        title="Preview">Pratinjau</button>
                                                                    <button type="button" class="text-danger"
                                                                        id="deleteBtn" title="Hapus">Hapus</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('file_path')
                                                        <small class="text-danger d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="form-group mt-3">
                                                    <label for="catatan">Resume Rencana Tindak Lanjut</label>
                                                    <input type="hidden" name="summary" id="summary">
                                                    <div id="editor"></div>
                                                    <br>
                                                    @error('summary')
                                                        <small class="text-danger d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="bg-primary text-white">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif



                                @if ($response && $response->score === null && $now->between($startDate, $endDate))
                                    <div class="card shadow-sm border-0 mt-4">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0 text-white">
                                                <i class="fa fa-edit"></i> Ubah Data Rencana Tindak Lanjut
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <form id="contentForm" enctype="multipart/form-data"
                                                action="{{ route('student.rtl.store', $item->id) }}" method="POST">
                                                @csrf

                                                <input type="hidden" name="follow_up_action_id"
                                                    value="{{ $item->id }}">

                                                <div class="form-group">
                                                    <div class="mt-2">
                                                        <label for="fileSurat" class="form-label">Unggah File Rencana Tindak
                                                            Lanjut</label>
                                                        <div class="upload-card">
                                                            <i class="fas fa-cloud-upload-alt"></i>
                                                            <h5 class="mb-3">Pilih berkas atau drag & drop di sini</h5>
                                                            <p class="text-muted">Hanya format PDF, maksimal 30MB</p>
                                                            <input name="file_path" type="file" id="fileInput"
                                                                accept=".pdf">
                                                            <label for="fileInput">Telusuri Berkas</label>
                                                        </div>

                                                        <div id="fileInfo" style="display: none;">
                                                            <div class="file-info">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-file-pdf fa-lg"></i>
                                                                    <span id="fileName" class="fw-bold"></span>
                                                                </div>
                                                                <div class="file-actions">
                                                                    <button type="button" id="previewBtn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#previewModal"
                                                                        title="Preview">Pratinjau</button>
                                                                    <button type="button" class="text-danger"
                                                                        id="deleteBtn" title="Hapus">Hapus</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @error('file_path')
                                                        <small class="text-danger d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="form-group mt-3">
                                                    <label for="catatan">Resume Rencana Tindak Lanjut</label>
                                                    <input type="hidden" name="summary" id="summary">
                                                    <div id="editor"></div>
                                                    <br>
                                                    @error('summary')
                                                        <small class="text-danger d-block">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="bg-primary text-white">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif


                            </div>
                        </div>


                    </div>

                    <div class="col-lg-4">
                        <!-- Customer Notes -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="h6">{{ __('Judul RTL') }}:<br /><span
                                        class="text-muted">{{ ucwords($item->title) }}</span></h3>
                                <p>{{ __('Deskripsi') }}:<br> {{ $item->description }}</p>
                            </div>
                        </div>

                        @if ($response->score !== null)
                            <!-- Score Information -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Catatan Penilain</h5>
                                    <p class="card-text"><strong>{{ $response->instructor_response }}</strong></p>
                                    <p class="card-text">nilai: {{ $response->score }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </section>


    <!-- Modal for PDF Preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="border: 1px solid #dee2e6">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Pratinjau File PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="pdfPreview" src="" style="width: 100%; height: 100vh;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        #editor {
            height: 300px;
            background-color: #fff;
        }

        #editor p {
            margin: 10px 0;
            /* Jarak antar paragraf */
            text-align: justify;
            /* Rapi dan rata kiri-kanan */
        }

        #editor img {
            max-width: 100%;
            /* Gambar responsif */
            height: auto;
            display: block;
            margin: 10px auto;
            /* Gambar tengah dan ada jarak */
        }

        .ql-snow .ql-color-picker .ql-picker-label,
        .ql-snow .ql-icon-picker .ql-picker-label {
            padding: 0px 3px;
        }

        .upload-card {
            border: 2px dashed #d3d3d3;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background-color: #fff;
            transition: border-color 0.3s;
            margin-bottom: 20px;
        }

        .upload-card:hover {
            border-color: #007bff;
        }

        .upload-card i {
            font-size: 40px;
            color: #6c757d;
        }

        .upload-card input[type="file"] {
            display: none;
        }

        .upload-card label {
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            padding: 5px 20px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .file-info {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .file-info i {
            margin-right: 10px;
            color: #e74c3c;
        }

        .file-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-actions button {
            background: none;
            border: none;
            cursor: pointer;
        }

        .file-actions button:hover {
            color: #007bff;
        }


        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.4);
            /* Transparan biar nggak terlalu gelap */
            z-index: -9999;
            /* Lebih rendah dari modal */
        }

        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            /* Lebih tinggi dari backdrop */
        }

        .modal-dialog {
            padding-top: 10px;
            max-width: 55%;
            width: 55%;
            height: 75%;
            margin: 0;
        }

        .modal-content {
            height: 100%;
            border: none;
            border-radius: 0;
            box-shadow: none;
            z-index: 1050;
            /* Lebih tinggi dari backdrop */
        }

        .modal-body {
            overflow: auto;
            /* Biar bisa scroll PDF */
        }

        body.modal-open {
            overflow: hidden;
            /* Disable scroll body belakang modal */
        }

        .select2-selection.select2-selection--single {
            height: 30px !important;
        }
    </style>
@endpush

@push('scripts')
    {{-- Quill JS dan Image Resize Module --}}
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>

    <script>
        // Inisialisasi Quill dengan Image Resize Module
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, false]
                    }],
                    [{
                            'align': []
                        },
                        'bold',
                        'italic',
                        'underline'
                    ],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }], // Ordered & Unordered List
                    ['link']
                ],
                // imageResize: {
                //     modules: ['Resize', 'DisplaySize', 'Toolbar']
                // }
            }
        });


        // // Tangani Submit Form
        const form = document.getElementById('contentForm');
        form.onsubmit = () => {
            const content = document.querySelector('#summary');
            const cleanHTML = quill.root.innerHTML.replace(/<p><br><\/p>/g, ''); // Bersihkan paragraf kosong
            content.value = cleanHTML; // Ambil HTML yang rapi dengan gambar Base64
        };

        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));

        document.getElementById('previewBtn').addEventListener('click', () => {
            previewModal.show();
        });

        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        const fileInfo = document.getElementById('fileInfo');
        const pdfPreview = document.getElementById('pdfPreview');
        const deleteBtn = document.getElementById('deleteBtn');

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (file && file.type === 'application/pdf') {
                fileName.textContent = file.name;
                fileInfo.style.display = 'block';

                // Rename file untuk pratinjau
                const renamedFile = new File([file], "dokumen-pratinjau.pdf", {
                    type: file.type
                });
                const fileURL = URL.createObjectURL(renamedFile);
                pdfPreview.src =
                    `${fileURL}#toolbar=1`; // Menampilkan toolbar tapi nama filenya diganti
            } else {
                alert('Silakan pilih file PDF!');
                fileInfo.style.display = 'none';
            }
        });

        // Hapus file yang diupload
        deleteBtn.addEventListener('click', () => {
            fileInput.value = "";
            fileInfo.style.display = 'none';
            pdfPreview.src = "";
        });
    </script>
@endpush
