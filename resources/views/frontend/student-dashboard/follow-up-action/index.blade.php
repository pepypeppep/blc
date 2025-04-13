@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between align-items-center">
            <h4 class="title d-flex justify-content-between align-items-center w-100">
                {{ __('Daftar Topik Rencana Tindak Lanjut (RTL)') }}

            </h4>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table id="enrollmentsRtlTable" class="table table-borderless">
                        <thead>
                            <tr>
                                <th width="30%">{{ __('Kursus') }}</th>
                                <th width="20%">{{ __('Materi') }}</th>
                                <th width="20%">{{ __('Topik RTL') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- datatables -->
    <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- datatables -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $('#enrollmentsRtlTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('student.follow-up-action.index') }}",
            columns: [{
                    data: 'course.title',
                    name: 'course.title'
                },
                {
                    data: 'chapter.title',
                    name: 'chapter.title'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false
                }
            ]
        });

        $(document).ready(function() {
            $(".delete-item").click(function() {
                let itemId = $(this).data("id");
                let deleteUrl = $(this).data("url");

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: "DELETE"
                            },
                            success: function(response) {
                                Swal.fire("Terhapus!", "Data berhasil dihapus.",
                                    "success").then((result) => {
                                    if (result.isConfirmed) {
                                        location
                                            .reload(); // Reload halaman setelah sukses
                                    }
                                });
                            },
                            error: function(xhr) {
                                Swal.fire("Error!", "Gagal menghapus data.", "error");
                            }
                        });
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
