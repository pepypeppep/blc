@extends('admin.master_layout')

@section('title')
    <title>{{ __('Knowledge Detail') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Knowledge Detail') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.knowledge.index') }}">{{ __('Knowledges') }}</a>
                </div>
                <div class="breadcrumb-item">{{ __('Knowledge Detail') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <!-- Content -->
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>{{ $article->title }}</h4>
                            <span class="badge badge-{{ $article->stat['color'] }}">{{ $article->stat['label'] }}</span>
                        </div>

                        @if($article->status === 'rejected')
                        <div class="alert alert-danger d-flex align-items-center m-4" style="background-color: rgba(220, 53, 69, 0.9);" role="alert">
                            <i class="fas fa-circle-exclamation fa-lg me-2"></i>
                            <div class="text-white">
                                <strong>Alasan Penolakan:</strong> 
                                <p>{{ $article->note }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="card-body">
                            @if ($article->category == 'video')
                                <iframe class="w-100" style="height: 70vh" src="{{ $article->embed_link }}" allowfullscreen></iframe>
                            @elseif ($article->category == 'document')
                                <object data="{{ $article->document_url }}" type="application/pdf" width="100%" height="600">
                                    <p><a href="{{ $article->document_url }}">Download PDF</a></p>
                                </object>  
                            @endif

                            <div class="pt-3">
                                {!! clean($article->description) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Related Course -->
                    @if($article->enrollment_id != NULL)
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Related Course') }}</h4>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('course.show', $article->enrollment->course->slug) }}" class="text-decoration-none text-dark" target="_blank">
                                    <div class="d-flex align-items-center" style="gap: 1rem;">
                                        <img src="{{ asset($article->enrollment->course->thumbnail) }}" alt="{{ $article->enrollment->course->title }}" style="width: 100px; height: 70px; object-fit: cover; border-radius: 0.25rem;">
                                        <div class="d-flex align-items-center ml-2">
                                            <h5 class="card-title m-0" style="font-size: 1rem;">{{ $article->enrollment->course->title }}</h5>
                                            <i class="fa fa-chevron-circle-right ml-1"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Verification -->
                    @if($article->status === 'verification')
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Verification') }}</h4>
                        </div>
                        <div class="card-body d-flex justify-content-between">
                            <form id="verify-form" action="{{ route('admin.knowledge.update-status', $article->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="published">
                                <button type="button" class="btn btn-success" onclick="confirmVerification()">
                                    <i class="fa fa-check mr-1"></i> {{ __('Verification') }}
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                <i class="fa fa-times mr-1"></i> {{ __('Reject') }}
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-3">
                    <!-- Thumbnail -->
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Thumbnail') }}</h4>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $article->thumbnail_url }}" alt="img" width="100%" style="cursor: pointer;"
                                data-toggle="modal" data-target="#thumbnailModal">
                        </div>
                    </div>

                    <!-- Article Info -->
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Information') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="flex items-start mb-1">
                                <strong class="w-40">{{ __('Category') }}:</strong>
                                <span class="badge badge-warning">{{ $article->category ?? '-' }}</span>
                            </div>
                            <div class="flex items-start mb-1">
                                <strong class="w-40">{{ __('Created Date') }}:</strong>
                                <span>{{ formatDate($article->created_at) }}</span>
                            </div>
                            <div class="flex items-start mb-1">
                                <strong class="w-40">{{ __('Views') }}:</strong>
                                <span>{{ $article->views }} kali</span>
                            </div>
                            <div class="flex items-start mb-1">
                                <strong class="w-40">{{ __('Allow Comment') }}:</strong>
                                <span>{{ $article->allow_comments ? 'Ya' : 'Tidak' }}</span>
                            </div>
                            <div class="flex items-start mb-1">
                                <strong class="w-40">{{ __('Visibility') }}:</strong>
                                <span class="badge badge-primary">
                                    {{ $article->visibility === 'public' ? 'Publik' : 'Internal' }}
                                </span>
                            </div>
                            <div class="flex items-start mb-1">
                                <strong class="w-40">{{ __('Published Date') }}:</strong>
                                <span>{{ formatDate($article->published_at) ?? '-'}}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Author Info -->
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Author') }}</h4>
                        </div>
                        <div class="card-body text-center">
                            <h6 class="mb-0">{{ $article->author->name }}</h6>
                            <small class="text-muted">{{ $article->author->email }}</small>
                            <div class="mt-2 text-muted">
                                {{ $article->instansi ?? '-' }}
                            </div>
                        </div>
                    </div>

                    @if($article->tags)
                    <!-- Tags -->
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Tags') }}</h4>
                        </div>
                        <div class="card-body">
                            @forelse ($article->tags ?? [] as $tag)
                                <span class="badge badge-primary">{{ $tag->tag->name }}</span>
                            @empty
                                <p class="text-muted">{{ __('Tidak ada tag.') }}</p>
                            @endforelse
                        </div>
                    </div>
                    @endif

                    @if(count($comments) > 0)
                    <!-- Comment -->
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Komentar') }} ({{ count($comments) }})</h4>
                        </div>
                        <div class="card-body">
                            @forelse ($comments as $comment)
                                <div class="border-bottom mb-3 pb-2">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">— {{ formatDate($comment->created_at) }}</small>
                                            <span><i class="fa fa-star" style="color: gold;"></i> {{ $comment->stars }}/5</span>
                                        </div>
                                    </div>

                                    <p class="mt-2 mb-0 short-text" id="short-{{ $comment->id }}">
                                        {{ truncate($comment->description) }}{{ strlen($comment->description) > 60 ? '...' : '' }}
                                    </p>

                                    <p class="mt-2 mb-0 full-text d-none" id="full-{{ $comment->id }}">
                                        {{ $comment->description }}
                                    </p>

                                    @if(strlen($comment->description) > 60)
                                        <a href="javascript:;" class="text-primary small" onclick="toggleComment({{ $comment->id }})" id="toggle-btn-{{ $comment->id }}">Lihat Selengkapnya</a>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted">{{ __('Tidak ada komentar.') }}</p>
                            @endforelse
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 25px 25px 0px 25px;">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ __('Reason') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.knowledge.update-status', $article->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="status" value="rejected">
                        <!-- <label for="rejected_reason">{{ __('Reason') }}</label> -->
                        <textarea name="rejected_reason" id="rejected_reason" rows="3" class="form-control"
                            placeholder="{{ __('Explain the reason for rejection...') }}" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">{{ __('Reject') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview -->
<div class="modal fade" id="thumbnailModal" tabindex="-1" aria-labelledby="thumbnailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{ $article->thumbnail_url }}" alt="Thumbnail" class="w-100 mb-3" style="display: block; border-radius: 0;">
                <div>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                    {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", "Success", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", "Error", {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
            });
        </script>
    @endif

    <script>
        function confirmVerification() {
            Swal.fire({
                title: 'Verifikasi Pengetahuan',
                text: "Apakah Anda yakin ingin memverifikasi pengetahuan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#ced4da',
                confirmButtonText: 'Ya, Verifikasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('verify-form').submit();
                }
            });
        }

        function toggleComment(id) {
            const shortText = document.getElementById('short-' + id);
            const fullText = document.getElementById('full-' + id);
            const btn = document.getElementById('toggle-btn-' + id);

            if (shortText.classList.contains('d-none')) {
                shortText.classList.remove('d-none');
                fullText.classList.add('d-none');
                btn.innerText = 'Lihat Selengkapnya';
            } else {
                shortText.classList.add('d-none');
                fullText.classList.remove('d-none');
                btn.innerText = 'Sembunyikan';
            }
        }
    </script>
@endpush