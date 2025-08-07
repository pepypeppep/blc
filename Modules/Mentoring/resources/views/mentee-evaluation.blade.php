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
                    <div class="breadcrumb-item">{{ __('Evaluation') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">{{ __('Pernyataan') }}</th>
                                            <th scope="col" class="text-center">{{ __('Nilai') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Target/Tujuan Tercapai</td>
                                            <td class="text-center">{{ $review->is_target ? __('Yes') : __('Tidak') }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Tingkat kedisiplinan</td>
                                            <td class="text-center">{{ $review->discipline }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>Tingkat kerjasama</td>
                                            <td class="text-center">{{ $review->teamwork }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">4</th>
                                            <td>Tingkat inisiatif</td>
                                            <td class="text-center">{{ $review->initiative }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">5</th>
                                            <td>Tingkat penguasaan materi</td>
                                            <td class="text-center">{{ $review->material_mastery }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row"></th>
                                            <td>Total Nilai</td>
                                            <td class="text-center">
                                                {{ $total }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"></th>
                                            <td>Nilai Akhir
                                                <strong class="badge badge-info">
                                                    {{ getMentorScoreLabel(round($total / 6, 2)) }}
                                                </strong>
                                            </td>
                                            <td class="text-center">
                                                {{ round($total / 6, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>{{ __('Referensi Nilai') }}</strong>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">{{ __('Nilai') }}</th>
                                                    <th scope="col">Kategori</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="row" class="text-center">0 - 20.99</th>
                                                    <td>Sangat Buruk</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="text-center">21 - 40.99</th>
                                                    <td>Buruk</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="text-center">41 - 60.99</th>
                                                    <td>Cukup</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="text-center">61 - 80.99</th>
                                                    <td>Baik</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" class="text-center">81 - 100</th>
                                                    <td>Sangat Baik</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>{{ __('Catatan') }}</strong>
                                    </div>
                                    <div class="col-md-12">
                                        {!! clean(@$review?->target_description) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
