@php
    use Modules\PendidikanLanjutan\app\Models\VacancyUser;
@endphp

<div class="tab-pane fade" id="pengaktifan-tab-pane" role="tabpanel" aria-labelledby="itemSeven-tab" tabindex="0">
    <div class="instructor__profile-form-wrap">
        <div class="row" id="attachment_container">
            <h5 class="heading-title">{{ __('Lampiran yang Diperlukan') }}</h5>
            <div class="col-12">
                <div class="dashboard__review-table mt-2 " style="overflow: hidden;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Jenis Lampiran') }}</th>
                                <th class="text-center">{{ __('Aksi') }}</th>
                                {{-- <th>{{ __('Berkas') }}</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activations as $activation)
                                @php
                                    $exist = $userActivation->where('vacancy_attachment_id', $activation->id)->first();
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $activation->name }} <span class="text-danger">*</span></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-1">

                                            @if ($exist)
                                                {!! $exist->getStatusLabel() !!}
                                                @if ($exist->status != 'accepted')
                                                    <form id="{{ $activation->id }}_form"
                                                        action="{{ route('student.continuing-education.registration.update-requirement-activation', ['vacancy_attachment_id' => $activation->id]) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input id="{{ $activation->id }}_file" type="file"
                                                            name="file"
                                                            @if ($activation->type == 'pdf') accept="application/pdf" @endif
                                                            disabled class="form-control me-2"
                                                            onchange="$('#{{ $activation->id }}_form').trigger('submit')"
                                                            hidden />
                                                        <button onclick="$('#{{ $activation->id }}_file').click()"
                                                            type="button" class="align-middle border-0 bg-transparent"
                                                            data-bs-toggle="tooltip" title="Unggah Ulang Berkas">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                    </form>
                                                    <form id="delete-form-{{ $exist->id }}"
                                                        action="{{ route('student.continuing-education.registration.delete-requirement-activation', ['vacancy_attachment_id' => $activation->id, 'user_activation_id' => $exist->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <a href="javascript:void(0);"
                                                        class="align-middle border-0 bg-transparent"
                                                        onclick="handleDeleteFormSubmit(event, 'delete-form-{{ $exist->id }}')">
                                                        <i class="fas fa-trash-alt text-danger"></i>
                                                    </a>
                                                    @endif
                                                @else
                                                    <form id="{{ $activation->id }}_form"
                                                        action="{{ route('student.continuing-education.registration.update-requirement-activation', ['vacancy_attachment_id' => $activation->id]) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="file" name="file"
                                                            @if ($activation->type == 'pdf') accept="application/pdf" @endif
                                                            class="form-control me-2"
                                                            onchange="$('#{{ $activation->id }}_form').trigger('submit')" />
                                                    </form>
                                                @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        function handleDeleteFormSubmit(event, formId) {
            event.preventDefault();
            swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan file yang telah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                } else {
                    swal.fire('Penghapusan dibatalkan', '', 'error');
                }
            })
        };
    </script>
@endpush
