@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <a href="{{ route('student.continuing-education') }}" class="btn mb-3 align-right">
        {{ __('Ajukan Pendaftaran') }}  <i class="fa fa-arrow-right"></i>
    </a>

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
                                <td>{{ $vacancy->start_at ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Tanggal Selesai Pendaftaran') }}</td>
                                <td>{{ $vacancy->end_at ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Status') }}</td>
                                <td>
                                    @if ($vacancy->users->firstWhere('id', auth()->id())->pivot->status === 'active')
                                        <div class="badge bg-success">{{ __('Approved') }}</div>
                                    @else
                                        <div class="badge bg-warning">{{ __('Pending') }}</div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
