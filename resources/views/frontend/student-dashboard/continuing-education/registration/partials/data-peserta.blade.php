<div class="tab-pane fade active show" id="itemOne-tab-pane" role="tabpanel" aria-labelledby="itemOne-tab" tabindex="0">
    <div class="dashboard__review-table " style="overflow: hidden;">
        <table class="table">
            <tbody>
                <tr>
                    <td>{{ __('NIP') }}</td>
                    <td>
                        {{ $vacancy->user->nip }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Nama Pegawai') }}</td>
                    <td>
                        {{ $vacancy->user->name }}
                    </td>

                </tr>
                <tr>
                    <td>{{ __('Jabatan / Pangkat') }}</td>
                    <td>
                        {{ userAuth()->jabatan }} / {{ userAuth()->pangkat }}
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
                        {{ $vacancy->user->instansi->name }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Jenjang/Program Studi') }}</td>
                    <td>
                        {{ $vacancy->vacancy->education_level }} / {{ $vacancy->vacancy->study->name }}
                    </td>
                </tr>
                <tr>
                    <td>{{ __('Tahun Tubel') }}</td>
                    <td>
                        {{ $vacancy->vacancy->year }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @if ($vacancy->status == 'register')
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
            <a href="{{ route('student.continuing-education.show', $vacancy->id) }}" class="btn mt-4 mb-3">
                {{ __('Ajukan Pendaftaran') }} <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
    @endif
</div>
