@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Detail Program Pendidikan Lanjutan') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>{{ __('Program Studi') }}</td>
                                <td>{{ $vacancy->details->first()->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Jenjang Pendidikan') }}</td>
                                <td>{{ $vacancy->details->first()->category ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Syarat Minimal Pangkat/Golongan') }}</td>
                                <td>{{ $vacancy->details->first()->type_value ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Mulai Pendaftaran') }}</td>
                                <td>{{ $vacancy->start_at ? \Carbon\Carbon::parse($vacancy->start_at)->format('d F Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Selesai Pendaftaran') }}</td>
                                <td>{{ $vacancy->end_at ? \Carbon\Carbon::parse($vacancy->end_at)->format('d F Y') : '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Div baru untuk status pemenuhan syarat -->
        <div class="row">
            <div class="col-12">
                <div class="mt-3 p-3">
                    <div class="courses__item-tag"
                        style="height: 80px; background: #EFEFF2; border-radius: 10px; padding: 7px 13px; display: flex; align-items: center; justify-content: center; text-align: center;">
                        {{-- @if ($vacancy->status) --}}
                        <p style="margin: 0; font-size: 17px; color: #161439; font-weight: 600;">Anda memenuhi semua syarat
                            Pendidikan Lanjutan</p>
                        {{-- @else
                            <p style="margin: 0; font-size: 17px; color: #161439; font-weight: 600;">Anda tidak memenuhi semua syarat untuk Pendidikan Lanjutan</p>
                        @endif --}}
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <a href="{{ route('student.continuing-education') }}" class="btn mt-4 mb-3">
                    {{ __('Ajukan Pendaftaran') }} <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="dashboard__review-table table-responsive mt-4">
                    <h5 class="title">{{ __('Lampiran yang Diperlukan') }}</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Jenis Lampiran') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Unggah Berkas') }}</th>
                                <th class="text-center">{{ __('Hasil Verifikasi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ __('Surat Pernyataan') }}</td>
                                <td>
                                    <span class="badge bg-success">{{ __('Lengkap') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                        {{-- <li class="about__info-list-item" data-bs-toggle="tooltip" title="Lihat berkas">
                                            <i class="fa fa-eye"></i>
                                        </li> --}}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        @if ($vacancy->is_active)
                                        <span style="display: inline-block; padding: 5px 10px; background-color: #d4edda; color: #155724; border-radius: 3px;">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 5px 10px; background-color: #f8d7da; color: #721c24; border-radius: 3px;">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>{{ __('SK Pangkat Terakhir') }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ __('Belum Lengkap') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                        {{-- <li class="about__info-list-item" data-bs-toggle="tooltip" title="Lihat berkas">
                                            <i class="fa fa-eye"></i>
                                        </li> --}}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        {{-- @if ($vacancy->is_active)  --}}
                                        <span style="display: inline-block; padding: 5px 10px; background-color: #d4edda; color: #155724; border-radius: 3px;">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    {{-- @else
                                        <span style="display: inline-block; padding: 5px 10px; background-color: #f8d7da; color: #721c24; border-radius: 3px;">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    @endif --}}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>{{ __('Ijazah Terakhir') }}</td>
                                <td>
                                    <span class="badge bg-success">{{ __('Lengkap') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <input type="file" name="attachment[sk_pangkat]" class="form-control me-2" />
                                        {{-- <li class="about__info-list-item" data-bs-toggle="tooltip" title="Lihat berkas">
                                            <i class="fa fa-eye"></i>
                                        </li> --}}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        {{-- @if ($vacancy->is_active) --}}
                                            <span style="display: inline-block; padding: 5px 10px; background-color: #d4edda; color: #155724; border-radius: 3px;">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        {{-- @else
                                            <span style="display: inline-block; padding: 5px 10px; background-color: #f8d7da; color: #721c24; border-radius: 3px;">
                                                <i class="fas fa-times"></i>
                                            </span>
                                        @endif --}}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
