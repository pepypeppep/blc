@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="dashboard__content-title d-flex justify-content-between">
            <h4 class="title">{{ __('Pengetahuan yang dibuat') }}</h4>
            <div>
                <button type="button" onclick="location.href='{{ route('student.pengetahuan.create') }}'"
                    class="btn">{{ __('Tambah Pengetahuan') }}</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="tab-content" id="courseTabContent">
                            @forelse ($pengetahuans as $pengetahuan)
                                <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel"
                                    aria-labelledby="all-tab" tabindex="0">
                                    <div class="dashboard-courses-active dashboard_courses">
                                        <div class="courses__item courses__item-two shine__animate-item">
                                            <div class="row align-items-center">
                                                <div class="col-xl-5">
                                                    <div class="courses__item-thumb courses__item-thumb-two">
                                                        <a href="{{ route('student.pengetahuan.edit', $pengetahuan->slug) }}"
                                                            class="shine__animate-link">
                                                            <img src="{{ route('student.pengetahuan.view.file', $pengetahuan->id) }}"
                                                                alt="img">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-xl-7">
                                                    <div class="courses__item-content courses__item-content-two">
                                                        <ul class="courses__item-meta list-wrap">
                                                            <li class="courses__item-tag">
                                                                <a href="javascript:;">{{ $pengetahuan->category }}</a>
                                                                <a href="javascript:;">{{ $pengetahuan->visibility }}</a>
                                                            </li>
                                                        </ul>

                                                        <h5 class="title"><a
                                                                href="{{ route('student.pengetahuan.edit', $pengetahuan->slug) }}">{{ $pengetahuan->title }}</a>
                                                        </h5>
                                                        @if (isset($pengetahuan->enrollment))
                                                            <div class="courses__item-content-bottom">
                                                                <div class="author-two">
                                                                    <a href="javascript:;"><img
                                                                            src="{{ asset($pengetahuan->enrollment->course->instructor->image) }}"
                                                                            onerror="this.src='{{ asset('frontend/img/instructor/h2_instructor01.png') }}'"
                                                                            alt="img">{{ $pengetahuan->enrollment->course->instructor->name }}</a>
                                                                </div>
                                                                <div class="avg-rating">
                                                                    <i class="fas fa-star"></i>
                                                                    {{ number_format($pengetahuan->enrollment->course->reviews()->avg('rating') ?? 0, 1) }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <span
                                                            class="badge bg-{{ $pengetahuan->stat['color'] }} mt-4">{{ $pengetahuan->stat['label'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <h6 class="text-center">{{ __('Belum Memiliki Pengetahuan') }}</h6>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
