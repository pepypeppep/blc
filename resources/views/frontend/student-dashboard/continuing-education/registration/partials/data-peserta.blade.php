<div class="tab-pane fade active show" id="itemOne-tab-pane" role="tabpanel" aria-labelledby="itemOne-tab" tabindex="0">
    <div class="dashboard__review-table " style="overflow: hidden;">
        <table class="table">
            <tbody>
                <tr>
                    <td>{{ __('NIP') }}</td>
                    <td>
                        {{ $vacancyUser->user->nip }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Nama Pegawai') }}</td>
                    <td>
                        {{ $vacancyUser->user->name }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Jabatan / Pangkat') }}</td>
                    <td>
                        {{ userAuth()->jabatan }} / {{ userAuth()->pangkat }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Batas Usia Pensiun') }}</td>
                    <td>
                        {{ userAuth()->bup }} Tahun
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Golongan') }}</td>
                    <td>
                        {{ userAuth()->golongan }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Unit Kerja') }}</td>
                    <td>
                        {{ $vacancyUser->user->instansi->name }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Jenjang/Program Studi') }}</td>
                    <td>
                        {{ $vacancyUser->vacancy->education_level }} / {{ $vacancyUser->vacancy->study->name }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Tahun Tubel') }}</td>
                    <td>
                        {{ $vacancyUser->vacancy->year }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @if ($vacancyUser->status == 'register')
    <div class="row">
        <div class="col-12 mt-4">
            <div class="p-3">
                <div style="background: #ffd3d3;"
                        class="d-flex align-items-center justify-content-center rounded-3 py-3 px-4 border border-danger">
                        <p class="mb-0 fs-5 text-dark fw-bold text-center">
                            Anda belum mengajukan pendaftaran
                        </p>
                    </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-end">
            <a href="{{ route('student.continuing-education.show', $vacancyUser->id) }}" class="btn mt-4 mb-3">
                {{ __('Ajukan Pendaftaran') }} <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
    @endif
</div>
