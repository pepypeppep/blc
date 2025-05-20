<section class="blog__post-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section__title text-center mb-40">
                    <span class="sub-title">{{ __('Knowledges') }}</span>
                    <h2 class="title">{{ __('Our Latest Knowledges') }}</h2>
                    <p>{{ __('Dont Miss Stay Updated with the Latest Articles and Insights') }}</p>
                </div>
            </div>
        </div>
        <div class="row gutter-20">
            @foreach ($featuredBlogs as $article)
                <div class="col-xxl-3 col-md-6 col-lg-4">
                    <div class="blog__post-item shine__animate-item" style="min-height: 440px;">
                        <div class="blog__post-thumb">
                            <a href="{{ route('article.show', $article->slug) }}" class="shine__animate-link blog">
                                <img src="{{ $article->thumbnail_url }}" alt="img">
                            </a>
                            <a href="{{ route('article', ['category' => $article->category]) }}"
                                class="post-tag">{{ $article->category }}</a>
                        </div>
                        <div class="courses__item-content">
                            <ul
                                class="courses__item-meta list-unstyled d-flex justify-content-between align-items-center">
                                <li><i class="flaticon-calendar"></i> {{ formatDate($article->created_at) }}</li>

                                <li class="d-flex align-items-center">
                                    <span class="me-2"><i class="fas fa-eye"></i>{{ $article->views }}</span>
                                    <span class="border-start px-2"></span>
                                    <span><i class="fas fa-star" style="color: #FFC107"></i> {{ $article->reviewsRating() }}</span>
                                </li>
                            </ul>
                            <h5 class="title">
                                <a
                                    href="{{ route('article.show', $article->slug) }}">{{ truncate($article->title, 50) }}</a>
                            </h5>
                            <ul class="list-wrap list-unstyled">
                                <li class="d-flex align-items-center">
                                    <i class="flaticon-user-1 me-2"></i>
                                    {{ __('by') }}
                                    <a href="javascript:;" class="ms-1">{{ truncate($article->author->name, 14) }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
