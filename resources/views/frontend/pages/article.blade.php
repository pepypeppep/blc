@extends('frontend.layouts.master')
@section('meta_title', $seo_setting['blog_page']['seo_title'])
@section('meta_description', $seo_setting['blog_page']['seo_description'])
@section('contents')
    <!-- breadcrumb-area -->
    <x-frontend.breadcrumb :title="__('Pengetahuan')" :links="[['url' => route('home'), 'text' => __('Home')], ['url' => route('blogs'), 'text' => __('Pengetahuan')]]" />
    <!-- breadcrumb-area-end -->

    <!-- blog-area -->
    <section class="blog-area section-py-120">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <div class="row gutter-20">
                        @forelse($articles as $article)
                            <div class="col-xl-4 col-md-6">
                                <div class="blog__post-item shine__animate-item">
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
                                                <span class="me-2"><i class="fas fa-eye"></i>652</span>
                                                <span class="border-start px-2"></span>
                                                <span><i class="fas fa-star" style="color: #FFC107"></i> 5.0</span>
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
                        @empty
                            <p class="text-center">{{ __('No Data Found') }}</p>
                        @endforelse
                    </div>
                    <nav class="pagination__wrap mt-25">
                        {{ $articles->links() }}
                    </nav>
                </div>
                {{-- <div class="col-xl-3 col-lg-4">
                    <aside class="blog-sidebar">
                        <div class="blog-widget widget_search">
                            <div class="sidebar-search-form">
                                <form action="{{ route('blogs') }}" method="get">
                                    <input type="text" placeholder="{{ __('Search here') }}" name="search"
                                        value="{{ request('search') }}">
                                    <button type="submit"><i class="flaticon-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="blog-widget">
                            <h4 class="widget-title">{{ __('Categories') }}</h4>
                            <div class="shop-cat-list">
                                <ul class="list-wrap">
                                    @forelse($categories as $category)
                                        <li>
                                            <a href="{{ route('article', ['category' => $category]) }}"><i
                                                    class="flaticon-angle-right"></i>{{ ucfirst($category) }}</a>
                                        </li>
                                    @empty
                                        <li>
                                            {{ __('No Category Found') }}
                                        </li>
                                    @endforelse
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
                            <h4 class="widget-title">{{ __('Popular Posts') }}</h4>
                            @forelse($popularArticles as $article)
                                <div class="rc-post-item">
                                    <div class="rc-post-thumb">
                                        <a href="{{ route('article.show', $article->slug) }}">
                                            <img class="h_60px" src="{{ asset($article->thumbnail) }}" alt="img">
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
                                <p class="">{{ __('No latest post yet') }}.</p>
                            @endforelse
                        </div>
                    </aside>
                </div> --}}
            </div>
        </div>
    </section>
    <!-- blog-area-end -->
@endsection
