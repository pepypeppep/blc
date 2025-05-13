<div class="modal-body">
    <form action="{{ route('admin.course-chapter.quiz-question.import-store', $quizId) }}" method="POST"
        enctype="multipart/form-data" class="import_lesson_form" id="importForm">
        @csrf

        <div class="mb-3 text-center">
            <label class="form-label fs-5 fw-bold">Import Pertanyaan dari Excel</label>
            <p class="text-muted">Unggah file Excel (.xlsx) berisi daftar pertanyaan quiz.</p>
        </div>




        <div class="alert alert-info small">
            <strong>Catatan:</strong>
            <ul class="list-unstyled">
                <li>Untuk jawaban benar berikan nilai <code>1</code>, untuk jawaban salah berikan nilai <code>0</code>.
                </li>
                <li>File harus dalam format <code>.xlsx</code>.</li>
                <li>Pastikan kolom sesuai dengan template seperti gambar dibawah ini:</li>
            </ul>
            <img src="{{ asset('backend/img/soal-example.png') }}" alt="Template Pertanyaan" class="img-fluid mt-3">
        </div>
        <div class="mb-3">
            <div class="border rounded p-3 text-center bg-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                    class="bi bi-file-earmark-arrow-up mb-2" viewBox="0 0 16 16">
                    <path
                        d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                    <path
                        d="M6.803 11.485A1 1 0 1 0 8.707 9.788l.647 1.646a.5.5 0 0 0 .708-.708l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0 .708.708l.647-1.646A1 1 0 0 0 6.803 11.485z" />
                </svg>
                <p class="mt-2">Pilih atau seret file Excel ke sini</p>
                <input type="file" name="excel_file" class="form-control mt-3" accept=".xlsx" />
            </div>
        </div>

        <div class="modal-footer d-flex justify-content-between">
            <a href="{{ asset('backend/template/contoh format soal.xlsx') }}" class="btn btn-outline-secondary"
                target="_blank" download>
                <i class="fas fa-download me-1"></i> Download Template Excel
            </a>
            <button type="submit" class="btn btn-primary">Import Soal</button>
        </div>
    </form>
</div>
