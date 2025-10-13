@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @if ($pengetahuan->status == 'rejected')
                        <div class="alert alert-danger" role="alert">
                            <span class="text text-capitalize">Status: {{ __($pengetahuan->stat['label']) }}</span>
                            <div>{!! clean($pengetahuan->note) !!}</div>
                        </div>
                    @else
                        <div class="alert alert-{{ $pengetahuan->stat['color'] }}" role="alert">
                            <span class="text text-capitalize">Status: {{ __($pengetahuan->stat['label']) }}</span>
                        </div>
                    @endif
                    <img src="{{ route('student.pengetahuan.view.file', $pengetahuan->id) }}"alt="img">

                    <h4 class="title text mt-4">{{ $pengetahuan->title }}</h4>
                    @if ($pengetahuan->enrollment)
                        <div class="col-12">
                            <span class="text text-capitalize"><strong>Pelatihan: </strong></span>
                            <p class="text mt-0">{{ $pengetahuan->enrollment->course->title }}</p>
                        </div>
                    @endif
                    @if ($pengetahuan->personal_certificate_recognition_id)
                        <div class="col-12">
                            <span class="text text-capitalize"><strong>Pengakuan Sertifikat: </strong></span>
                            <p><a href="{{ route('student.pengakuan-sertifikat.show', $pengetahuan->certificateRecognition->id) }}"
                                    class="text mt-0">{{ $pengetahuan->certificateRecognition->title }}</a></p>
                        </div>
                    @endif
                    <div>
                        <span class="badge bg-primary">{{ $pengetahuan->category }}</span>
                        <span
                            class="badge bg-{{ $pengetahuan->visibility == 'public' ? 'primary' : 'warning' }}">{{ $pengetahuan->visibility == 'public' ? 'Public' : 'Internal' }}</span>
                    </div>
                    @if ($pengetahuan->articleTags)
                        <div class="col-12 mt-2">
                            <span class="text text-capitalize"><strong>Tags </strong></span>
                            @foreach ($pengetahuan->articleTags as $tag)
                                <span class="badge bg-secondary">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                    <hr>
                    <div class="col-12">
                        <span class="text text-capitalize"><strong>Deskripsi</strong></span>
                        <p class="text mt-0">{!! clean($pengetahuan->description) !!}</p>
                    </div>
                    @if ($pengetahuan->category == 'video')
                        <div class="col-12">
                            <span class="text text-capitalize"><strong>Link</strong></span>
                            <p class="text mt-0">Lihat di <a href="{{ $pengetahuan->link }}"
                                    target="_blank">{{ $pengetahuan->link }}</a></p>
                        </div>
                    @endif
                    @if ($pengetahuan->category == 'document')
                        <div class="col-12">
                            <span class="text text-capitalize"><strong>File</strong></span>
                            <p class="text mt-0"><a href="{{ route('student.pengetahuan.view.file', $pengetahuan->id) }}"
                                    target="_blank">Lihat File</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
