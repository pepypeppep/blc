@extends('frontend.student-dashboard.layouts.master')

@section('dashboard-contents')
    <div class="dashboard__content-wrap">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @if ($pengetahuan->status == 'rejected')
                        <div class="alert alert-danger" role="alert">
                            <span class="text text-capitalize">Status: {{ $pengetahuan->stat['label'] }}</span>
                            <span class="alert-heading">Alasan</span>
                            <p>{!! clean($pengetahuan->note) !!}</p>
                        </div>
                    @else
                        <div class="alert alert-{{ $pengetahuan->stat['color'] }}" role="alert">
                            <span class="text text-capitalize">Status: {{ $pengetahuan->stat['label'] }}</span>
                        </div>
                    @endif
                    <img src="{{ route('student.pengetahuan.view.file', $pengetahuan->id) }}"alt="img">

                    <h4 class="title text mt-4">{{ $pengetahuan->title }}</h4>
                    <div>
                        <span class="badge bg-primary">{{ $pengetahuan->category }}</span>
                        <span
                            class="badge bg-{{ $pengetahuan->visibility == 'public' ? 'primary' : 'warning' }}">{{ $pengetahuan->visibility == 'public' ? 'Public' : 'Internal' }}</span>
                    </div>
                    <hr>
                    <div class="col-12">
                        <span class="text text-capitalize"><strong>Deskripsi</strong></span>
                        <p class="text mt-0">{!! clean($pengetahuan->description) !!}</p>
                    </div>
                    @if($pengetahuan->category == 'video')
                    <div class="col-12">
                        <span class="text text-capitalize"><strong>Link</strong></span>
                        <p class="text mt-0">Lihat di <a href="{{ $pengetahuan->link }}" target="_blank">{{ $pengetahuan->link }}</a></p>
                    </div>
                    @endif
                    @if($pengetahuan->category == 'document')
                    <div class="col-12">
                        <span class="text text-capitalize"><strong>File</strong></span>
                        <p class="text mt-0"><a href="{{ route('student.pengetahuan.view.file', $pengetahuan->id) }}" target="_blank">Lihat File</a></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
