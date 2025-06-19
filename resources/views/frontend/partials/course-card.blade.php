@forelse ($courses as $course)
    <div class="col-xxl-4 col-md-6 col-lg-6 col-xl-6">
        <div class="courses__item shine__animate-item position-relative">
            <div class="courses__item-thumb position-relative"
                style="height: 260px; overflow: hidden; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <!-- Badge Type (Kiri Atas) -->
                <span
                    class="position-absolute top-0 start-0 m-2 px-3 py-1 text-dark fw-semibold small rounded-pill text-capitalize"
                    style="font-size: 12px; background-color: rgba(218, 233, 158, 0.85); z-index: 10;">
                    {{ $course->type == 'course' ? 'Kursus' : ucwords($course->type) }}
                </span>

                <!-- Wishlist Icon (Kanan Atas) -->
                <a href="javascript:;" class="position-absolute top-0 end-0 m-2" style="z-index: 10; color: #ff69b4;"
                    data-slug="{{ $course?->slug }}" aria-label="WishList">
                    <i class="{{ $course?->favorite_by_client ? 'fas' : 'far' }} fa-heart fa-lg"></i>
                </a>

                <!-- Gambar -->
                <a href="{{ route('course.show', $course->slug) }}" class="d-block w-100 h-100">
                    <img src="{{ $course->thumbnail_url }}" alt="img" class="w-100 h-100"
                        style="object-fit: cover; display: block;">
                </a>
            </div>

            <div class="courses__item-content">
                <ul class="courses__item-meta list-wrap">
                    <li class="courses__item-tag">
                        <a href="{{ route('courses', ['category' => $course->category->id]) }}">
                            {{ $course->category->translation->name }}
                        </a>
                    </li>
                    <li class="avg-rating"><i class="fas fa-star"></i>
                        {{ number_format($course->reviews()->avg('rating'), 1) ?? 0 }}
                    </li>
                </ul>
                <h5 class="title">
                    <a href="{{ route('course.show', $course->slug) }}">{{ truncate($course->title, 50) }}</a>
                </h5>
                <p class="author">{{ __('By') }}
                    @if ($course->instructor)
                        <a
                            href="{{ route('instructor-details', ['id' => $course->instructor->id, 'slug' => Str::slug($course->instructor->name)]) }}">
                            {{ $course->instructor->name }}
                        </a>
                    @else
                        Tim Diklat
                    @endif
                </p>
                <div class="courses__item-bottom d-flex justify-content-between align-items-center">
                    <span
                        class="badge {{ $course->access === 'private' ? 'bg-danger' : 'bg-success' }} text-white px-3 py-1 rounded-pill"
                        style="font-size: 12px;">
                        {{ $course->access === 'private' ? 'Internal' : 'Publik' }}
                    </span>
                    <div class="button">
                        <a href="{{ route('course.show', $course->slug) }}" class="already-enrolled-btn"
                            data-id="">
                            <span class="text">Lihat Detail</span>
                            <i class="flaticon-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@empty
    <div class="w-100">
        <h6 class="text-center">{{ __('No Course Found!') }}</h6>
    </div>
@endforelse
