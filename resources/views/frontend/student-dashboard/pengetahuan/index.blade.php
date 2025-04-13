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
                                <div class="tab-pane fade show active position-relative" id="all-tab-pane" role="tabpanel"
                                    aria-labelledby="all-tab" tabindex="0">
                                    <div class="dashboard-courses-active dashboard_courses ">
                                        <div class="courses__item courses__item-two shine__animate-item">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4">
                                                    <div class="courses__item-thumb courses__item-thumb-two">
                                                        <a href="{{ route('student.pengetahuan.show', $pengetahuan->slug) }}"
                                                            class="shine__animate-link">
                                                            <img src="{{ route('student.pengetahuan.view.file', $pengetahuan->id) }}"
                                                                alt="img">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4">
                                                    <div class="courses__item-content courses__item-content-two">
                                                        <h5 class="title"><a
                                                                href="{{ route('student.pengetahuan.show', $pengetahuan->slug) }}">{{ $pengetahuan->title }}</a>
                                                        </h5>
                                                        @if ($pengetahuan->enrollment)
                                                        <span><strong>Pelatihan:</strong> {{ optional(optional($pengetahuan->enrollment)->course)->title }}</span>
                                                        @endif
                                                        <h6 class="sub-title">
                                                            {!! Str::limit(clean($pengetahuan->description), 75, '...') !!}
                                                        </h6>
                                                        <div>
                                                            @foreach ($pengetahuan->articleTags as $tag)
                                                                <span
                                                                    class="badge bg-secondary mt-2">{{ $tag->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4">
                                                    <div
                                                        class="courses__item-content courses__item-content-two align-items-end text-end">
                                                        <div>
                                                            <span
                                                                class="badge bg-primary mt-2 uppercase text-capitalize">{{ $pengetahuan->category }}</span>
                                                            <span
                                                                class="badge bg-{{ $pengetahuan->visibility == 'public' ? 'primary' : 'warning' }} mt-2 uppercase text-capitalize">{{ $pengetahuan->visibility == 'public' ? 'Public' : 'Internal' }}</span>
                                                        </div>
                                                        <div>
                                                            &nbsp;
                                                        </div>
                                                        <div class="d-flex justify-content-end text-ebd items-end gap-2">
                                                            @if ($pengetahuan->status != 'verification' && $pengetahuan->status != 'published')
                                                                <div class="courses__item-bottom">
                                                                    <form id="update-form-{{ $pengetahuan->id }}"
                                                                        action="{{ route('student.pengetahuan.ajukan', $pengetahuan->slug) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="button">
                                                                            <a onclick="$('#update-form-{{ $pengetahuan->id }}').submit()"
                                                                                class="already-enrolled-btn" data-id="">
                                                                                <span class="text">Ajukan</span>
                                                                                <i class="flaticon-arrow-right"></i>
                                                                            </a>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="courses__item-bottom">
                                                                    <div class="button">
                                                                        <a href="{{ route('student.pengetahuan.edit', $pengetahuan->slug) }}"
                                                                            class="already-enrolled-btn" data-id="">
                                                                            <i class="fa fa-pencil-alt"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="courses__item-bottom">
                                                                    <form id="delete-form-{{ $pengetahuan->id }}"
                                                                        action="{{ route('student.pengetahuan.destroy', $pengetahuan->slug) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div class="button">
                                                                            <a onclick="deletePengetahuan(event, {{ $pengetahuan->id }})"
                                                                                class="already-enrolled-btn bg-danger" data-id="">
                                                                                <i class="fa fa-trash text-white"></i>
                                                                            </a>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            @else
                                                                <div class="courses__item-bottom">
                                                                    <div class="button">
                                                                        <a href="{{ route('student.pengetahuan.show', $pengetahuan->slug) }}"
                                                                            class="already-enrolled-btn" data-id="">
                                                                            <span class="text">Lihat Detail</span>
                                                                            <i class="flaticon-arrow-right"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            @if ($pengetahuan->status == 'rejected')
                                                <div class="mt-2 alert alert-danger" role="alert">
                                                    <span class="alert-heading">Alasan</span>
                                                    <p>{!! clean($pengetahuan->note) !!}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Watermark -->
                                    @if ($pengetahuan->status != 'published')
                                        <div
                                            class="watermark position-absolute text-capitalize text-{{ $pengetahuan->status == 'draft' ? 'secondary' : ($pengetahuan->status == 'rejected' ? 'danger' : 'warning') }} opacity-25">
                                            {{ $pengetahuan->status }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <h6 class="text-center">{{ __('Belum Memiliki Pengetahuan') }}</h6>
                            @endforelse
                            <div class="d-flex justify-content-center">
                                {{ $pengetahuans->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .watermark {
            font-size: 4rem;
            font-weight: bold;
            transform: translate(-50%, -50%) rotate(-45deg);
            /* Center and diagonal placement */
            top: 50%;
            left: 50%;
            position: absolute;
            z-index: 2;
            pointer-events: none;
            padding: 10px;
            /* Adds spacing inside the border */
            white-space: nowrap;
            /* Prevents text wrapping */
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deletePengetahuan(event, id) {
            swal.fire({
                title: "Apakah kamu yakin ingin menghapus pengetahuan ini?",
                text: "Anda tidak dapat mengembalikan pengetahuan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "##5751e1",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
