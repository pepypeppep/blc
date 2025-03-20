@extends('admin.master_layout')
@section('title')
    <title>{{ __($submenu) }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __($submenu) }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __($submenu) }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Registrant List') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class=" max-h-400">
                                    <div class="row mb-2">
                                        <div class="col-md-10"></div>
                                        <div class="col-auto ms-auto text-end">
                                            <a href="javascript:history.back()"
                                                class="btn btn-primary btn-md m-2 d-flex align-items-center w-auto">
                                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                                            </a>
                                        </div>
                                    </div>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>{{ __('#') }}</th>
                                                <th>{{ __('Employee Id') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Employment Unit') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Reason') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($rejectedUsers as $rejectedUser)
                                                <tr data-user-id="{{ $rejectedUser->user->id }}">
                                                    <td><input type="checkbox" class="userCheckbox"
                                                            value="{{ $rejectedUser->user->id }}"></td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $rejectedUser->user->nip }}</td>
                                                    <td>{{ $rejectedUser->user->name }}</td>
                                                    <td>Dinas Komunikasi dan Informatika</td>
                                                    <td>
                                                        @if ($rejectedUser->has_access == 1)
                                                            <span class="badge badge-success">Diterima</span>
                                                        @elseif ($rejectedUser->has_access == 0)
                                                            <span class="badge badge-danger">Ditolak</span>
                                                        @else
                                                            <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($rejectedUser->has_access == 0)
                                                            {{ $rejectedUser->notes ?? 'Tidak ada alasan yang diberikan' }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm m-1 updateStatus"
                                                            data-id="{{ $rejectedUser->user->id }}" data-status="1">
                                                            <i class="fa fa-check" aria-hidden="true"></i> Terima
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        {{ __('No registrants found!') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-admin.delete-modal />
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

    @push('js')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                function showReasonModal(userIds, status) {
                    let title;
                    let isReasonRequired = false;

                    if (status === 1) {
                        title = "Alasan Penerimaan (Opsional)";
                    } else if (status === 0) {
                        title = "Alasan Penolakan";
                        isReasonRequired = true; // Alasan wajib diisi jika ditolak
                    } else {
                        title = "Reset ke Pending";
                    }

                    Swal.fire({
                        title: title,
                        input: "textarea",
                        inputPlaceholder: "Masukkan alasan...",
                        showCancelButton: true,
                        confirmButtonText: "Lanjutkan",
                        cancelButtonText: "Batal",
                        preConfirm: (reason) => {
                            if (isReasonRequired && !reason) {
                                Swal.showValidationMessage("Alasan harus diisi jika peserta ditolak.");
                                return false;
                            }
                            return reason;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showConfirmation(userIds, status, result.value);
                        }
                    });
                }

                function showConfirmation(userIds, status, reason) {
                    let message;
                    if (status === 1) {
                        message = "Peserta akan diterima.";
                    } else if (status === 0) {
                        message = "Peserta akan ditolak.";
                    } else {
                        message = "Status peserta akan dikembalikan ke Pending.";
                    }

                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: message,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, lanjutkan!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateEnrollment(userIds, status, reason);
                        }
                    });
                }

                async function updateEnrollment(userIds, status, reason) {
                    if (userIds.length === 0) {
                        return Swal.fire("Peringatan", "Pilih minimal 1 peserta.", "warning");
                    }

                    try {
                        let response = await fetch("{{ route('admin.course.updateEnrollmentStatus') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                user_ids: userIds,
                                status: status,
                                reason: reason || null // Alasan opsional untuk diterima
                            })
                        });

                        let data = await response.json();
                        Swal.fire("Sukses", data.message, "success");

                        // Hapus baris dari tabel setelah status diperbarui
                        userIds.forEach(userId => {
                            let row = document.querySelector(`tr[data-user-id='${userId}']`);
                            if (row) {
                                row.remove();
                            }
                        });

                    } catch (error) {
                        Swal.fire("Error", "Terjadi kesalahan, coba lagi.", "error");
                    }
                }

                document.querySelectorAll(".updateStatus").forEach(button => {
                    button.addEventListener("click", function() {
                        let userId = this.dataset.id;
                        let status = this.dataset.status === "1" ? 1 : (this.dataset.status === "0" ?
                            0 : null);
                        showReasonModal([userId], status);
                    });
                });

                document.getElementById("acceptAll").addEventListener("click", function() {
                    let selectedUsers = Array.from(document.querySelectorAll(".userCheckbox:checked"))
                        .map(checkbox => checkbox.value);
                    showReasonModal(selectedUsers, 1);
                });

                document.getElementById("rejectAll").addEventListener("click", function() {
                    let selectedUsers = Array.from(document.querySelectorAll(".userCheckbox:checked"))
                        .map(checkbox => checkbox.value);
                    showReasonModal(selectedUsers, 0);
                });

                document.getElementById("resetAll").addEventListener("click", function() {
                    let selectedUsers = Array.from(document.querySelectorAll(".userCheckbox:checked"))
                        .map(checkbox => checkbox.value);
                    showReasonModal(selectedUsers, null);
                });

                document.getElementById("selectAll").addEventListener("change", function() {
                    let isChecked = this.checked;
                    document.querySelectorAll(".userCheckbox").forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                });
            });
        </script>
    @endpush
@endpush


@push('css')
    <style>
        .dd-custom-css {
            position: absolute;
            will-change: transform;
            top: 0px;
            left: 0px;
            transform: translate3d(0px, -131px, 0px);
        }

        .max-h-400 {
            min-height: 400px;
        }
    </style>
@endpush
