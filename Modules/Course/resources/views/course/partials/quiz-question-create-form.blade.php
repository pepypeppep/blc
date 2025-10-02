@extends('admin.master_layout')

@section('custom_meta')
    <meta name="course_id" content="{{ request('id') }}">
@endsection

@section('title')
    <title>{{ __('Course Create') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="text-primary"> Buat Soal Kuis</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ url('admin/courses/create/' . $quiz->course_id . '/step/3') }}">Daftar 
                            Pelatihan</a>
                    </div>
                    <div class="breadcrumb-item"> Buat Soal Kuis </div>
                </div>
            </div>
            <div class="section-body">
                <div class="dashboard__content-wrap">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        Informasi Kuis
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered align-middle">
                                            <tr>
                                                <th width="180">Judul</th>
                                                <td>{{ $quiz->title }}</td>
                                                <th width="180">Waktu</th>
                                                <td>{{ $quiz->time }} menit</td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Percobaan</th>
                                                <td>{{ $quiz->attempt }}</td>
                                                <th>Nilai Kelulusan</th>
                                                <td>{{ $quiz->pass_mark }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Nilai</th>
                                                <td>{{ $quiz->total_mark }}</td>
                                                <th>Status</th>
                                                <td>
                                                    @if ($quiz->status == 'active')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Batas Waktu</th>
                                                <td>{{ \Carbon\Carbon::parse($quiz->due_date)->format('d M Y H:i') }}</td>
                                                <th>Terakhir Diperbarui</th>
                                                <td>{{ $quiz->updated_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <!-- Main Area -->
                            <div class="col-md-8">
                                <div class="card shadow-sm">
                                    {{-- {{ !empty($questionItem) ? $questionItem->id : '' }} --}}
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Kelola Pertanyaan</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="mainQuestionForm" enctype="multipart/form-data">
                                            <input type="hidden" name="question_id" id="question_id"
                                                value="{{ !empty($questionItem) ? $questionItem->id : '' }}">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Pertanyaan</label>
                                                <div id="question-editor" class="border rounded p-2 quill-editor">
                                                </div>
                                                <input type="hidden" name="question_text" id="question_text">
                                            </div>

                                            @if (empty($questionItem))
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Bobot Nilai</label>
                                                    <input type="number" name="weight" id="modal_question_weight"
                                                        class="form-control" min="1" value="1">
                                                </div>
                                            @else
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Bobot Nilai</label>
                                                    <input type="number" name="weight" id="modal_question_weight"
                                                        class="form-control" min="1"
                                                        value="{{ $questionItem->grade }}">
                                                </div>
                                            @endif

                                            <div class="mb-4">
                                                <label class="form-label fw-semibold">Gambar Pertanyaan</label>
                                                <div class="form-check form-switch mb-2">
                                                    <input class="form-check-input" type="checkbox" id="toggleQuestionImage"
                                                        {{ !empty($questionItem) && $questionItem->image ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="toggleQuestionImage">
                                                        Gambar Pertanyaan
                                                    </label>
                                                </div>

                                                <div id="questionImageContainer"
                                                    class="{{ !empty($questionItem) && $questionItem->image ? '' : 'd-none' }}">
                                                    <div class="image-upload-container mb-2">
                                                        {{-- Placeholder hanya tampil kalau belum ada gambar --}}
                                                        <div class="img-placeholder {{ !empty($questionItem) && $questionItem->image ? 'd-none' : '' }}"
                                                            id="questionImagePlaceholder"
                                                            onclick="document.getElementById('question_image').click()">
                                                            <i class="fa fa-plus fa-2x text-muted"></i>
                                                            <p class="mt-2 mb-0 small">Klik untuk menambah gambar</p>
                                                        </div>

                                                        {{-- Preview tampil kalau ada image --}}
                                                        <div class="image-preview {{ !empty($questionItem) && $questionItem->image ? '' : 'd-none' }}"
                                                            id="questionImagePreview">
                                                            <img id="questionPreviewImg"
                                                                src="{{ !empty($questionItem) && $questionItem->image ? asset($questionItem->image) : '' }}"
                                                                alt="Preview">
                                                            <button type="button" class="btn-remove-image"
                                                                onclick="removeQuestionImage()">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <input type="file" name="question_image" id="question_image"
                                                        class="d-none" accept="image/*"
                                                        onchange="previewQuestionImage(this)">
                                                </div>
                                            </div>


                                            <hr>
                                            <h5 class="mb-3">Jawaban</h5>
                                            <div id="answers-container"></div>
                                            <button type="button" class="btn btn-outline-primary mt-2"
                                                onclick="addAnswer()">
                                                <i class="fa fa-plus me-1"></i> Tambah Jawaban
                                            </button>

                                            <div class="mt-4 d-flex justify-content-end gap-2">
                                                {{-- <button type="button" class="btn btn-secondary"
                                                    id="btnResetForm">Reset</button> --}}
                                                <button type="button" class="btn btn-success"
                                                    onclick="saveQuestionAndAnswers()">
                                                    <i class="fa fa-save me-1"></i> Simpan Pertanyaan & Jawaban
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Sidebar: List Pertanyaan -->
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Daftar Pertanyaan</h6>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#addQuestionModal">
                                            <i class="fa fa-plus"></i> Tambah
                                        </button>

                                    </div>
                                    <div class="card-body p-0">
                                        <div class="list-group list-group-flush">

                                            @if (!empty($questions))
                                                <ol class="list-group quiz-list">
                                                    @foreach ($questions as $question)
                                                        <li class="list-group-item d-flex align-items-start">
                                                            <span class="quiz-number me-2">{{ $loop->iteration }}.</span>
                                                            <a class="ml-2"
                                                                href="{{ route('admin.course-chapter.quiz-question.create', [$quiz->id, 'questionId' => $question->id]) }}">
                                                                <span>{!! $question->title !!}</span>
                                                                <span>({{ $question->grade }})</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                                {{-- @if (count($questions) == 15)
                                                    <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                        data-target="#searchQuestionModal">
                                                        <i class="fa fa-search"></i> Cari Pertanyaan
                                                    </button>
                                                @endif --}}
                                            @else
                                                <div class="empty-state p-3 text-center">
                                                    <i class="fas fa-question-circle text-muted mb-2"
                                                        style="font-size: 2rem;"></i>
                                                    <p class="text-muted">Belum ada pertanyaan</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Add Question -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1" role="dialog" aria-labelledby="addQuestionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="modalQuestionForm" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addQuestionModalLabel">Tambah Pertanyaan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pertanyaan</label>
                            <div id="modal-question-editor" class="border rounded p-2 quill-editor"></div>
                            <input type="hidden" name="title" id="modal_question_text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bobot Nilai</label>
                            <input type="number" name="weight" id="modal_question_weight" class="form-control"
                                min="1" value="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Gambar Pertanyaan</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="toggleModalQuestionImage">
                                <label class="form-check-label" for="toggleModalQuestionImage">Aktifkan Gambar</label>
                            </div>
                            <div id="modalQuestionImageContainer" class="d-none">
                                <div class="image-upload-container mb-2">
                                    <div class="img-placeholder" id="modalQuestionImagePlaceholder"
                                        onclick="document.getElementById('modal_question_image').click()">
                                        <i class="fa fa-plus fa-2x text-muted"></i>
                                        <p class="mt-2 mb-0 small">Klik untuk menambah gambar</p>
                                    </div>
                                    <div class="image-preview d-none" id="modalQuestionImagePreview">
                                        <img id="modalQuestionPreviewImg" src="" alt="Preview">
                                        <button type="button" class="btn-remove-image"
                                            onclick="removeModalQuestionImage()">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="file" name="image" id="modal_question_image" class="d-none"
                                    accept="image/*" onchange="previewModalQuestionImage(this)">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="saveQuestionOnly()">
                            <i class="fa fa-save me-1"></i> Simpan Pertanyaan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        let answerIndex = 0;
        let currentQuestionId = null;
        let quillInstances = {};

        // Initialize Quill editors
        let quillMain = new Quill('#question-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: 'Tulis pertanyaan di sini...'
        });

        // isi dari database

        let initialMainContent = @json($questionItem->title ?? null);

        if (initialMainContent !== null) {
            quillMain.root.innerHTML = initialMainContent;
        }

        let quillModal = new Quill('#modal-question-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['link', 'image'],
                    ['clean']
                ]
            },
            placeholder: 'Tulis pertanyaan di sini...'
        });

        //init question answer 
        let questionAnswers = @json($questionAnswer ?? []);


        document.addEventListener('DOMContentLoaded', function() {
            if (typeof questionAnswers !== 'undefined' && questionAnswers.length > 0) {
                questionAnswers.forEach(ans => addAnswer(ans));
            } else {
                addAnswer();
                addAnswer(); // default 2 jawaban kosong
            }

            // Setup event listeners
            document.getElementById('toggleQuestionImage').addEventListener('change', function() {
                document.getElementById('questionImageContainer').classList.toggle('d-none', !this.checked);
                if (!this.checked) {
                    removeQuestionImage();
                }
            });

            document.getElementById('toggleModalQuestionImage').addEventListener('change', function() {
                document.getElementById('modalQuestionImageContainer').classList.toggle('d-none', !this
                    .checked);
                if (!this.checked) {
                    removeModalQuestionImage();
                }
            });

            // Load questions if any
            // loadQuestions();
        });


        function addAnswer(existing = null) {
            let index = answerIndex++;
            let text = existing ? existing.title : '';
            let checked = existing && existing.correct ? 'checked' : '';
            let imgSrc = existing && existing.image ? `${existing.image}` : '';

            let html = `
    <div class="card mb-3 p-3 shadow-sm answer-card" id="answer-${index}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Jawaban ${index + 1}</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAnswer(${index})">
                <i class="fa fa-trash"></i>
            </button>
        </div>

        <div class="mb-3">
            <label class="form-label">Teks Jawaban</label>
            <div id="answerEditor-${index}" class="quill-editor"></div>
            <input type="hidden" name="answers[${index}][text]" id="answerInput-${index}" value="${text}">
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="answers[${index}][is_correct]" id="answer-correct-${index}" ${checked}>
                    <label class="form-check-label fw-semibold" for="answer-correct-${index}">Jawaban Benar</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="toggleAnswerImage-${index}" onchange="toggleAnswerImage(${index}, this.checked)" ${imgSrc ? 'checked' : ''}>
                    <label class="form-check-label" for="toggleAnswerImage-${index}">Gambar Jawaban</label>
                </div>
            </div>
        </div>

        <div id="answerImageContainer-${index}" class="${imgSrc ? '' : 'd-none'}">
            <div class="image-upload-container mb-2">
                <div class="img-placeholder small ${imgSrc ? 'd-none' : ''}" id="answerImagePlaceholder-${index}" onclick="document.getElementById('answer_image_${index}').click()">
                    <i class="fa fa-plus text-muted"></i>
                    <p class="mt-1 mb-0 small">Tambah Gambar</p>
                </div>
                <div class="image-preview small ${imgSrc ? '' : 'd-none'}" id="answerImagePreview-${index}">
                    <img id="answerPreviewImg-${index}" src="${imgSrc}" alt="Preview">
                    <button type="button" class="btn-remove-image" onclick="removeAnswerImage(${index})">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <input type="file" name="answers[${index}][image]" id="answer_image_${index}" class="d-none" accept="image/*" onchange="previewAnswerImage(${index}, this)">
        </div>
    </div>`;

            document.getElementById('answers-container').insertAdjacentHTML('beforeend', html);

            // Init Quill untuk editor ini
            let quill = new Quill(`#answerEditor-${index}`, {
                theme: 'snow',
                placeholder: 'Tulis jawaban di sini...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['link']
                    ]
                }
            });
            quill.root.innerHTML = text; // isi awal
            quill.on('text-change', function() {
                document.getElementById(`answerInput-${index}`).value = quill.root.innerHTML;
            });
        }



        // Remove Answer
        function removeAnswer(index) {
            document.getElementById(`answer-${index}`).remove();
        }

        // Toggle Answer Image
        function toggleAnswerImage(index, show) {
            const container = document.getElementById(`answerImageContainer-${index}`);
            container.classList.toggle('d-none', !show);

            if (!show) {
                removeAnswerImage(index);
            }
        }
        // function toggleAnswerImage(index, checked) {
        //     const container = document.getElementById(`answerImageContainer-${index}`);
        //     container.classList.toggle('d-none', !checked);

        //     // jangan hapus gambar, cukup hide saja
        // }

        // function toggleQuestionImage(checked) {
        //     const container = document.getElementById('questionImageContainer');
        //     container.classList.toggle('d-none', !checked);

        //     // gambar lama tetap ada, jangan dipanggil removeQuestionImage()
        // }

        // Preview Question Image
        function previewQuestionImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('questionPreviewImg').src = e.target.result;
                    document.getElementById('questionImagePreview').classList.remove('d-none');
                    document.getElementById('questionImagePlaceholder').classList.add('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove Question Image
        function removeQuestionImage() {
            document.getElementById('question_image').value = '';
            document.getElementById('questionImagePreview').classList.add('d-none');
            document.getElementById('questionImagePlaceholder').classList.remove('d-none');
        }

        // Preview Modal Question Image
        function previewModalQuestionImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('modalQuestionPreviewImg').src = e.target.result;
                    document.getElementById('modalQuestionImagePreview').classList.remove('d-none');
                    document.getElementById('modalQuestionImagePlaceholder').classList.add('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove Modal Question Image
        function removeModalQuestionImage() {
            document.getElementById('modal_question_image').value = '';
            document.getElementById('modalQuestionImagePreview').classList.add('d-none');
            document.getElementById('modalQuestionImagePlaceholder').classList.remove('d-none');
        }

        // Preview Answer Image
        function previewAnswerImage(index, input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(`answerPreviewImg-${index}`).src = e.target.result;
                    document.getElementById(`answerImagePreview-${index}`).classList.remove('d-none');
                    document.getElementById(`answerImagePlaceholder-${index}`).classList.add('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove Answer Image
        function removeAnswerImage(index) {
            document.getElementById(`answer_image_${index}`).value = '';
            document.getElementById(`answerImagePreview-${index}`).classList.add('d-none');
            document.getElementById(`answerImagePlaceholder-${index}`).classList.remove('d-none');
        }

        // Reset Form
        function resetForm() {
            quillMain.root.innerHTML = '';
            document.getElementById('question_weight').value = 1;
            document.getElementById('question_id').value = '';
            document.getElementById('toggleQuestionImage').checked = false;
            document.getElementById('questionImageContainer').classList.add('d-none');
            removeQuestionImage();

            // Clear answers
            document.getElementById('answers-container').innerHTML = '';
            answerIndex = 0;
            addAnswer();

            // Reset current question
            currentQuestionId = null;

            // Remove active class from question list
            document.querySelectorAll('.question-item').forEach(item => {
                item.classList.remove('active');
            });
        }

        // Load Questions
        // function loadQuestions() {
        //     // Simulate loading questions (replace with actual API call)
        //     // For demo purposes, we'll just show a message
        //     const questionList = document.getElementById('question-list');
        //     questionList.innerHTML = `
    //         <div class="empty-state p-3 text-center">
    //             <i class="fas fa-question-circle text-muted mb-2" style="font-size: 2rem;"></i>
    //             <p class="text-muted">Belum ada pertanyaan</p>
    //             <p class="small text-muted">Klik tombol "Tambah" untuk membuat pertanyaan pertama</p>
    //         </div>
    //     `;
        // }

        // Save Question Only (from modal)
        function saveQuestionOnly() {
            document.getElementById('modal_question_text').value = quillModal.root.innerHTML;
            let form = document.getElementById('modalQuestionForm');
            let formData = new FormData(form);

            // Simulate API call
            for (let pair of formData.entries()) {
                console.log(`${pair[0]}: ${pair[1]}`);
            }

            // Send AJAX request to save question only
            $.ajax({
                url: "{{ route('admin.course-chapter.quiz-question-only.store', [$quizId]) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Close modal
                            $('#addQuestionModal').modal('hide');

                            if (response.callback_url) {
                                window.location.href = response.callback_url;
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = Object.values(errors).flat().join('<br>');

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi gagal',
                            html: errorMessages
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Silakan coba lagi nanti.'
                        });
                    }
                }
            });
        }

        // Save Question with Answers
        function saveQuestionAndAnswers() {
            document.getElementById('question_text').value = quillMain.root.innerHTML;
            let form = document.getElementById('mainQuestionForm');
            let formData = new FormData(form);

            // Validasi pertanyaan
            if (!quillMain.getText().trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops',
                    text: 'Pertanyaan tidak boleh kosong!'
                });
                return;
            }

            // Validasi minimal 1 jawaban benar
            const correctAnswers = document.querySelectorAll('input[name*="[is_correct]"]:checked');
            if (correctAnswers.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops',
                    text: 'Pilih setidaknya satu jawaban yang benar!'
                });
                return;
            }

            $.ajax({
                url: "{{ route('admin.course-chapter.quiz-question-answer.store', [$quiz->id]) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            if (response.callback_url) {
                                window.location.href = response.callback_url;
                            } else {
                                // reset form kalau tidak redirect
                                resetForm();
                                // loadQuestions();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = Object.values(errors).flat().join('<br>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi gagal',
                            html: errorMessages
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Silakan coba lagi nanti.'
                        });
                    }
                }
            });
        }
    </script>
@endpush

@push('css')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .quill-editor {
            border-radius: 0.5rem;
            height: 150px;
            margin-bottom: 1rem;
        }

        .ql-toolbar.ql-snow {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            border: 1px solid var(--border);
        }

        .ql-container.ql-snow {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            border: 1px solid var(--border);
            border-top: none;
        }

        .image-upload-container {
            position: relative;
            display: inline-block;
        }

        .img-placeholder {
            width: 200px;
            height: 150px;
            border: 2px dashed #ced4da;
            border-radius: 0.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background-color: #f8f9fa;
            transition: all 0.2s ease;
            text-align: center;
            padding: 1rem;
        }

        .img-placeholder:hover {
            border-color: var(--primary);
            background-color: var(--primary-light);
        }

        .img-placeholder.small {
            width: 120px;
            height: 90px;
            padding: 0.5rem;
        }

        .image-preview {
            position: relative;
            display: inline-block;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: var(--shadow);
            width: 200px;
            height: 150px;
        }

        .image-preview img {
            width: 100%;
            /* Ubah dari max-width menjadi width */
            height: 100%;
            /* Ubah dari max-height menjadi height */
            display: block;
            object-fit: cover;
        }

        .image-preview.small {
            width: 120px;
            /* Tambahkan ini */
            height: 90px;
            /* Tambahkan ini */
        }

        .image-preview.small img {
            width: 100%;
            /* Ubah dari max-width menjadi width */
            height: 100%;
            /* Ubah dari max-height menjadi height */
            object-fit: cover;
        }

        .btn-remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-remove-image:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .answer-card {
            border-left: 4px solid var(--primary);
            transition: all 0.2s ease;
        }

        .answer-card:hover {
            border-left-color: var(--success);
        }

        .list-group-item {
            border: none;
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.25rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .list-group-item:hover {
            background-color: var(--primary-light);
        }

        .list-group-item.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .empty-state {
            color: #6c757d;
            padding: 2rem 1rem;
        }

        .modal-content {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            background-color: var(--primary);
            color: white;
            border-bottom: none;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            padding: 1.25rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
        }

        .close {
            color: white;
            opacity: 0.8;
            font-size: 1.5rem;
        }

        .close:hover {
            opacity: 1;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-switch .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        hr {
            border-top: 1px solid var(--border);
            opacity: 1;
        }

        @media (max-width: 768px) {
            .section-header {
                padding: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .d-flex.justify-content-end {
                flex-direction: column;
            }
        }

        .quiz-list .list-group-item {
            padding: 0.35rem 0.75rem;
            border: none;
        }

        .quiz-list .quiz-number {
            font-weight: 600;
            min-width: 1.5rem;
            /* kasih lebar tetap biar rapi */
            text-align: right;
        }

        .quiz-list a p {
            display: inline;
            margin: 0;
        }
    </style>
@endpush
