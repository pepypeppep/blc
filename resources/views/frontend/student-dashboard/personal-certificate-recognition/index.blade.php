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
                                    <td>{{ $data->competency_development->name }}</td>
                                    <td>{{ $data->jp }}</td>
                                    <td class="text-center">
                                        <div class="badge bg-{{ $data->stat['color'] }}">
                                            {{ $data->stat['label'] }}</div>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="d-flex justify-content-center text-end items-end gap-2">
                                            <div class="courses__item-bottom m-0">
                                                <div class="button">
                                                    <a href="{{ route('student.pengakuan-sertifikat.show', $data->id) }}"
                                                        class="already-enrolled-btn bg-primary" data-id="">
                                                        <i class="fa fa-eye text-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="courses__item-bottom m-0">
                                                <form id="delete-form-{{ $data->id }}"
                                                    action="{{ route('student.pengakuan-sertifikat.destroy', $data->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="button">
                                                        <a onclick="deletePengakuanSertifikat(event, {{ $data->id }})"
                                                            class="already-enrolled-btn bg-danger" data-id=""
                                                            style="cursor: pointer;">
                                                            <i class="fa fa-trash text-white"></i>
                                                        </a>
                                                    </div>
                                                </form>
                                            </div>
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
                title: "Apakah kamu yakin ingin menghapus pengakuan sertifikat ini?",
                text: "Anda tidak dapat mengembalikan pengakuan sertifikat ini!",
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
