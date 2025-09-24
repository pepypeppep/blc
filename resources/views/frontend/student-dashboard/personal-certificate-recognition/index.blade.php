@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Pengakuan Sertifikat yang dibuat') }}</h4>
            <div>
                <button type="button" onclick="location.href='{{ route('student.pengakuan-sertifikat.create') }}'"
                    class="btn">{{ __('Tambah Pengakuan Sertifikat') }}</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Competency') }}</th>
                                <th>{{ __('JP') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengakuans as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ $data->competency_development_id }}</td>
                                    <td>{{ $data->jp }}</td>
                                    <td class="text-center">
                                        <div class="badge bg-{{ $data->stat['color'] }}">
                                            {{ $data->stat['label'] }}</div>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="dashboard__mentee-action d-inline-flex align-items-center gap-2">
                                            <a href="{{ route('student.pengetahuan.show', $data->id) }}"
                                                class="btn-action-primary" title="Lihat Detail">
                                                <i class="fa fa-eye"></i> &nbsp;Lihat
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('Belum memiliki pengakuan sertifikat') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $pengakuans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
    </style>
@endpush

@push('scripts')
    <script>
        function deletePengakuanSertifikat(event, id) {
            swal.fire({
                title: "Apakah kamu yakin ingin menghapus pengetahuan ini?",
                text: "Anda tidak dapat mengembalikan pengetahuan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "##5751e1",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
