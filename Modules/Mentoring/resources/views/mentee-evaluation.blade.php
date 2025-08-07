@extends('admin.master_layout')
@section('title')
    <title>{{ __('Mentee Evaluation') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Mentee Evaluation') }}: "{{ $mentoring->mentee->name }}"</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Certificate Recognition') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Target/Tujuan Tercapai') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        <span>{{ @$review->is_target ? __('Yes') : __('Tidak') }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Catatan') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {!! clean(@$review?->target_description) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Tingkat Kedisiplinan') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        <span>{{ @$review->discipline }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Catatan') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {!! clean(@$review?->discipline_description) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Tingkat Kerjasama') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        <span>{{ @$review->teamwork }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Catatan') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {!! clean(@$review?->teamwork_description) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Tingkat Initiatif') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        <span>{{ @$review->initiative }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Catatan') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {!! clean(@$review?->initiative_description) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Tingkat Penguasaan Materi') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        <span>{{ @$review->material_mastery }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>{{ __('Catatan') }}</strong>
                                    </div>
                                    <div class="col-md-10">
                                        {!! clean(@$review?->material_mastery_description) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row mt-3">
                    <div class="col"><a href="{{ route('admin.mentoring.show', $mentoring->id) }}"
                            class="btn btn-primary"><i class="fas fa-arrow-left"></i> {{ __('Kembali ke Mentoring') }}</a>
                    </div>
                </div> --}}
            </div>
        </section>
    </div>
@endsection
