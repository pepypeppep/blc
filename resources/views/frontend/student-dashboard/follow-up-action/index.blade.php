@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between align-items-center">
            <h4 class="title d-flex justify-content-between align-items-center w-100">
                {{ __('Daftar Program Pendidikan Lanjutan') }}
                <span class="dashboard__content-title-button">
                    <a href="{{ route('student.follow-up-action.create') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus"></i> {{ __('Buat Rencana Tindak Lanjut') }}
                    </a>
                </span>
            </h4>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th width="30%">{{ __('Kursus') }}</th>
                                <th width="20%">{{ __('Ringkasan') }}</th>
                                <th width="20%">{{ __('Dibuat') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->course->title }}</td>
                                    <td>{!! Str::words($item->summary, 8) !!} ...</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('student.follow-up-action.show', $item->id) }}"
                                            class="align-middle" data-bs-toggle="tooltip" title="Lihat selengkapnya">
                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                        </a>

                                        <a href="{{ route('student.follow-up-action.edit', $item->id) }}"
                                            class="align-middle" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>


                                        <button type="button" class="delete-item "
                                            style="width: 32px; height: 32px; background-color: #dc3545; color: #fff; border-radius: 50%; 
                                                       border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center;"
                                            data-id="{{ $item->id }}"
                                            data-url="{{ route('student.follow-up-action.destroy', $item->id) }}"
                                            data-bs-toggle="tooltip" title="Hapus">
                                            <i class="fa fa-trash text-white"></i>
                                        </button>

                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('No data found!') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
