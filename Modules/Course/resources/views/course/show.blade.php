@extends('admin.master_layout')

@section('title')
    <title>{{ __('Course Detail') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Course Detail') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.knowledge.index') }}">{{ __('Courses') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Course Detail') }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <!-- Content -->
                    <div class="col-lg-9">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Course') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <label for=""><strong>Judul</strong></label>
                                        <div>{{ $course->title }}</div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <label for=""><strong>Tipe Pelatihan</strong></label>
                                        <div>{{ $course->type }}</div>
                                    </div>
                                    <div class="col-6">
                                        <label for=""><strong>Instansi</strong></label>
                                        <div>{{ $course->instansi }}</div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-4">
                                        <label for=""><strong>Kapasitas</strong></label>
                                        <div>{{ $course->capacity ?? '-' }}</div>
                                    </div>
                                    <div class="col-4">
                                        <label for=""><strong>Kategori</strong></label>
                                        <div>{{ $course->category->slug }}</div>
                                    </div>
                                    <div class="col-4">
                                        <label for=""><strong>Akses Pelatihan</strong></label>
                                        <div>{{ $course->access }}</div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-4">
                                        <label for=""><strong>Pelaksanaan</strong></label>
                                        <div>
                                            {{ \Carbon\Carbon::parse($course->start_date)->format('d F Y') . ' - ' . \Carbon\Carbon::parse($course->end_date)->format('d F Y') }}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for=""><strong>Tingkatan</strong></label>
                                        <div>
                                            {{ \Modules\Course\app\Models\CourseLevel::find($course->levels->first()->level_id)->slug }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for=""><strong>Latar Belakang</strong></label>
                                        <div>{!! $course->background ?? '-' !!}</div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for=""><strong>Dasar Hukum</strong></label>
                                        <div>{!! $course->dasar_hukum ?? '-' !!}</div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for=""><strong>Tujuan Pelatihan</strong></label>
                                        <div>{!! $course->description ?? '-' !!}</div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for=""><strong>Output dan Outcome Pelatihan</strong></label>
                                        <div>{!! $course->output ?? '-' !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-3">
                        <!-- Thumbnail -->
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Thumbnail') }}</h4>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ $course->thumbnail_url }}" alt="img" width="100%"
                                    style="cursor: pointer;" data-toggle="modal" data-target="#thumbnailModal">
                            </div>
                        </div>

                        <!-- Instructor -->
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Instructor') }}</h4>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tbody>
                                        @foreach ($course->all_instructors as $instructor)
                                            <tr>
                                                <td><strong>{{ __('Name') }}</strong></td>
                                                <td>{{ $instructor->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Participants') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('NIP/NIK') }}</th>
                                                    <th>{{ __('Instansi') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($course->enrollments as $enrollment)
                                                    <tr>
                                                        <td>{{ $enrollment->user->name }}</td>
                                                        <td>{{ $enrollment->user->nip ?? $enrollment->user->nik }}</td>
                                                        <td>{{ $enrollment->user->instansi?->name }}</td>
                                                        <td>
                                                            @if ($enrollment->has_access == null)
                                                                <a href="{{ route('admin.course-verification.index', $course->id) }}"
                                                                    class="btn btn-sm btn-primary">Verifikasi</a>
                                                            @elseif ($enrollment->has_access == 1)
                                                                <span class="badge badge-success">Aktif</span>
                                                            @elseif ($enrollment->has_access == 0)
                                                                <span class="badge badge-danger">Tidak Aktif</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">
                                                            {{ __('No Data') }}
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
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

@push('js')
@endpush
