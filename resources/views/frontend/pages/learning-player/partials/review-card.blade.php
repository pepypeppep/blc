@forelse ($reviews as $review)
<div class="review-part">
    <div class="course-review-head">
        <div class="review-author-thumb">

            <style>
                .review-author-thumb {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background-color:rgb(134, 118, 224);
                    color: white;
                    font-size: 20px;
                    font-weight: bold;
                    text-transform: uppercase;
                }

                .review-author-thumb img {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    object-fit: cover;
                }

            </style>

            @php
                $initials = strtoupper(Str::of($review->user->name)->explode(' ')->map(fn($part) => strtoupper(substr($part, 0, 1)))->implode(''));
            @endphp

            @if($review->user->image)
                <img src="{{ asset($review->user->image) }}" alt="img">
            @else
                <div class="initials">{{ $initials }}</div>
            @endif
        </div>
        <div class="review-author-content">
            <div class="author-name">
                <h5 class="name">{{ $review->user->name }}
                    @if($review->status === 0)
                        <span class="badge bg-info text-white" title="Ulasan ini hanya dapat dilihat oleh Anda">Menunggu Verifikasi Admin</span>
                    @endif
                </h5>
                <div class="author-rating">
                    @for($i = 0; $i < $review->rating; $i++)
                    <i class="fas fa-star"></i>
                    @endfor
                </div>
            </div>
            {!! clean($review->review) !!} <br>
            <small>{{ formattedDateTime($review->created_at) }}</small>
        </div>
    </div>
</div>
@empty
<p class="text-center">{{ __('No reviews!') }}</p>
@endforelse
