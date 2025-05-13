@extends('frontend.layouts.master')
@section('meta_title', $article->title . ' || ' . $setting->app_name)

@push('custom_meta')
    <meta name="description" content="{{ $article->description }}">
    <meta property="keywords" content="{{ $article->tags }}" />
    <meta property="og:title" content="{{ $article->title }}" />
    <meta property="og:description" content="{{ $article->description }}" />
    <meta property="og:image" content="{{ $article->thumbnail_url }}" />
    <meta property="og:URL" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/shareon.min.css') }}">
@endpush
@section('meta_title', $setting->app_name . ' | ' . $article->title)
@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Detail Pengetahuan')" :links="[
        ['url' => route('home'), 'text' => __('Home')],
        ['url' => route('blogs'), 'text' => __('Pengetahuan')],
        ['url' => '', 'text' => $article->title],
    ]" />
    <!-- breadcrumb-area-end -->

    <!-- blog-details-area -->
    <section class="blog-details-area section-py-120">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <div class="blog__details-wrapper">
                        <div class="blog__details-thumb">
                            @if ($article->category == 'video')
                                <iframe class="w-100" style="height: 70vh" src="{{ $article->embed_link }}" allowfullscreen></iframe>
                            @elseif ($article->category == 'document')
                                <object data="{{ $article->document_url }}" type="application/pdf" width="100%"
                                    height="600">
                                    <p><a href="{{ $article->document_url }}">Download PDF</a></p>
                                </object>
                            @else
                                <img src="{{ $article->thumbnail_url }}" alt="img">
                            @endif
                        </div>
                        <div class="blog__details-content">
                            <div class="blog__post-meta">
                                <ul class="list-wrap">
                                    <li><i class="flaticon-calendar"></i> {{ formatDate($article->created_at) }}</li>
                                    <li><i class="flaticon-user-1"></i> {{ __('by') }} <a
                                            href="javascript:;">{{ $article->author->name }}</a></li>
                                    <li><i class="flaticon-clock"></i>
                                        {{ calculateReadingTime($article->description) }} {{ __('View') }}
                                    </li>
                                    <li><i class="far fa-comment-alt"></i> {{ count($comments) }} {{ __('Comments') }}
                                    </li>
                                    <li><i class="fas fa-star"></i> {{ $article->reviewsRating() }} </li>
                                    <li><i class="far fa-eye"></i> {{ $article->views }}
                                </ul>
                            </div>
                            <h3 class="title">{{ $article->title }}</h3>
                            <p>
                                {!! clean($article->description) !!}
                            </p>
                            <div class="blog__details-bottom">
                                <div class="row">
                                    <div class="col-xl-6 col-md-7">
                                        <div class="tg-post-tag">
                                            @if ($article->tags)
                                                <h5 class="tag-title">{{ __('Tags ') }}:</h5>
                                                <ul class="list-wrap p-0 mb-0">
                                                    @foreach ($article->tags ?? [] as $tag)
                                                        <li><a href="javascript:;">{{ $tag->tag->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-5">
                                        <div class="tg-post-social justify-content-start justify-content-md-end">
                                            <h5 class="social-title">{{ __('Share') }} :</h5>
                                            <ul class="list-wrap p-0 mb-0">
                                                <div class="shareon">
                                                    <a class="facebook"></a>
                                                    <a class="linkedin"></a>
                                                    <a class="pinterest"></a>
                                                    <a class="telegram"></a>
                                                    <a class="twitter"></a>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="blog__post-author">
                        <div class="blog__post-author-thumb">
                            <a href="#"><img
                                    src="https://asn.bantulkab.go.id/images/simpeg/fotopns/{{ $article->author->nip }}.jpg"
                                    alt="img"></a>
                        </div>
                        <div class="blog__post-author-content">
                            <span class="designation">{{ __('Author') }}</span>
                            <h5 class="name">{{ $article->author->name }}</h5>
                            <p>{{ $article->author->bio }}</p>
                        </div>
                    </div>
                    <div class="blog-post-comment">
                        @if ($article->allow_comments == 1)
                            @auth
                                <div class="comment-respond">
                                    <h4 class="comment-reply-title">{{ __('Post a comment') }}</h4>
                                    <div class="comment-note">
                                        <p>{{ __('Please keep your comment under 1000 characters') }}</p>
                                    </div>
                                    <form action="{{ route('blog.submit-comment') }}" class="comment-form" method="post">
                                        @csrf
                                        <input type="hidden" name="blog_id" value="{{ $article->id }}">
                                        <div class="form-grp mb-3">
                                            <label for="">{{ __('Rate this course') }} <code>*</code></label>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div class="rating-stars d-flex justify-content-start flex-row-reverse">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <input type="radio" id="star{{ $i }}" name="rating"
                                                            value="{{ $i }}"
                                                            {{ old('rating') == $i ? 'checked' : '' }} />
                                                        <label for="star{{ $i }}" class="star">&#9733;</label>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment-field">
                                            <label for="">Komentar</label>

                                            <textarea placeholder="{{ __('Tulis Komentar') }}" name="comment"></textarea>
                                        </div>
                                        <!-- g-recaptcha -->
                                        @if (Cache::get('setting')->recaptcha_status === 'active')
                                            <div class="form-grp mt-3">
                                                <div class="g-recaptcha"
                                                    data-sitekey="{{ Cache::get('setting')->recaptcha_site_key }}"></div>
                                            </div>
                                        @endif

                                        <p class="form-submit"></p>
                                        <button class="btn btn-two arrow-btn">{{ __('Post Comment') }} <img
                                                src="{{ asset('frontend/img/icons/right_arrow.svg') }}" alt="img"
                                                class="injectable"></button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-primary d-flex align-items-center" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                                        viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                        <path
                                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </svg>
                                    <div>
                                        {{ __('Please login to comment') }}
                                    </div>
                                </div>
                            @endauth
                        @endif
                        <div class="comment-wrap mt-5">
                            @if (count($comments) > 0)
                                <div class="comment-wrap-title">
                                    <h4 class="title">{{ count($comments) }} {{ __('Comments') }}</h4>
                                </div>
                            @endif
                            <div class="latest-comments">
                                @foreach ($comments as $comment)
                                    <ul class="list-wrap">
                                        <li>
                                            <div class="comments-box">
                                                <div class="comments-avatar">
                                                    <img src="https://asn.bantulkab.go.id/images/simpeg/fotopns/{{ $comment->user->nip }}.jpg"
                                                        alt="img">
                                                </div>
                                                <div class="comments-text">
                                                    <div class="avatar-name">
                                                        <h6 class="name">{{ $comment->user->name }}</h6>
                                                        <span class="date">{{ formatDate($comment->created_at) }}</span>
                                                    </div>
                                                    <p>{{ $comment->description }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <hr class="col-12 border">
                                @endforeach
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-xl-3 col-lg-4">
                    <aside class="blog-sidebar">
                        <div class="blog-widget widget_search">
                            <div class="sidebar-search-form">
                                <form action="{{ route('blogs') }}" method="get">
                                    <input type="text" placeholder="{{ __('Search here') }}" name="search">
                                    <button type="submit"><i class="flaticon-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="blog-widget">
                            <h4 class="widget-title">{{ __('Categories') }}</h4>
                            <div class="shop-cat-list">
                                <ul class="list-wrap">
                                    @foreach ($categories as $category)
                                        <li>
                                            <a href="{{ route('article', ['category' => $category]) }}"><i
                                                    class="flaticon-angle-right"></i>{{ $category }}</a>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                        <div class="blog-widget">
                            <h4 class="widget-title">{{ __('Tags') }}</h4>
                            <div class="shop-cat-list">
                                <ul class="list-wrap">
                                    @forelse($tags as $tag)
                                        <li>
                                            <a href="{{ route('article', ['tag' => $tag->name]) }}"><i
                                                    class="flaticon-angle-right"></i>{{ ucfirst($tag->name) }}</a>
                                        </li>
                                    @empty
                                        <li>
                                            {{ __('No Tag Found') }}
                                        </li>
                                    @endforelse

                                </ul>
                            </div>
                        </div>
                        <div class="blog-widget">
                            <h4 class="widget-title">{{ __('Latest Post') }}</h4>
                            @forelse($latestBlogs as $article)
                                <div class="rc-post-item">
                                    <div class="rc-post-thumb">
                                        <a href="{{ route('article.show', $article->slug) }}">
                                            <img class="h_60px" src="{{ $article->thumbnail_url }}" alt="img">
                                        </a>
                                    </div>
                                    <div class="rc-post-content">
                                        <span class="date"><i class="flaticon-calendar"></i>
                                            {{ formatDate($article->created_at) }}</span>
                                        <h4 class="title"><a
                                                href="{{ route('article.show', $article->slug) }}">{{ truncate($article->title, 30) }}</a>
                                        </h4>
                                    </div>
                                </div>
                            @empty
                                <p>{{ __('No latest post yet') }}.</p>
                            @endforelse
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
    <!-- blog-details-area-end -->
@endsection

@push('scripts')
    <script src="{{ asset('frontend/js/shareon.iife.js') }}"></script>

    <script>
        Shareon.init();
    </script>
@endpush

@push('styles')
    <style>
        .rating-stars {
            gap: 5px;
        }

        .rating-stars .star {
            font-size: 2.5rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .rating-stars input[type="radio"] {
            display: none;
        }

        .rating-stars input[type="radio"]:checked~.star,
        .rating-stars input[type="radio"]:checked+label~label {
            color: #ffc107;
        }
    </style>
@endpush
