@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title">
            <h4 class="title">{{ __('Informasi Dokumen') }}</h4>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12 info-wrapper">
                                    <div class="row w-100">
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <div class="invoice-title">
                                                <h2>{{ __('Data Peserta') }}</h2>
                                                <div class="invoice-number">{{ __('Nomor Pendaftaran ') }} #{{ $vacancy->id }}
                                                </div>
                                                <address>
                                                    <strong>{{ __('Tanggal Pendaftaran') }}:</strong><br>
                                                    {{ \Carbon\Carbon::parse($vacancy->start_at)->format('d-m-Y') }}<br><br>
                                                </address>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <address>
                                                <strong>{{ __('Data Peserta') }}:</strong><br>
                                                {{ auth()->user()->name }}<br>
                                                {{ auth()->user()->email }}<br>
                                            </address>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <address>
                                                <strong>{{ __('Program Studi') }}:</strong><br>
                                                {{ $vacancy->name }}<br>
                                            </address>
                                            <address>
                                                <strong>{{ __('Jenjang Pendidikan') }}:</strong><br>
                                                {{ $vacancy->details->first()->category  }}<br><br>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="section-title">{{ __('Dokumen SK') }}</div>
                                    <div class="document-viewer">
                                        {{-- @if($attachment) --}}
                                            <embed src="https://www.buds.com.ua/images/Lorem_ipsum.pdf" type="application/pdf" width="100%" height="600px">
                                        {{-- @else
                                            <p>{{ __('No PDF document available') }}</p>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-md-right">
                            <a target="_blank" href="https://www.buds.com.ua/images/Lorem_ipsum.pdf" class="btn btn-warning btn-icon icon-left print-btn"><i class="fas fa-print"></i>
                                {{ __('Print') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
