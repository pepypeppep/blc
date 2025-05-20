
<a href="javascript:;" class="cart-count notification-toggle" data-bs-toggle="dropdown" aria-expanded="false">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 22C13.1 22 14 21.1 14 20H10C10 21.1 10.9 22 12 22ZM18 16V11C18 7.93 16.37 5.36 13.5 4.68V4C13.5 3.17 12.83 2.5 12 2.5C11.17 2.5 10.5 3.17 10.5 4V4.68C7.64 5.36 6 7.92 6 11V16L4 18V19H20V18L18 16Z" fill="currentColor"/>
    </svg>
    <span class="mini-cart-count" id="notificationCounter">{{ $counter }}</span>
</a>
<ul class="dropdown-menu notification-dropdown">
    <li class="dropdown-header">
        <span>{{ __('Notifications') }}</span>
    </li>
    <li class="divider"></li>
    <!-- Notification Items (max 5) -->
    @forelse ($notifications as $notif)
    <li>
        <a href="{{ $notif->link ?? 'javascript:void(0);' }}">
            <div class="notification-item">
                @if ($notif->status == 'rejected')
                <div class="notification-icon bg-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                @elseif ($notif->status == 'approved' || $notif->status == 'accepted')
                <div class="notification-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                @else
                <div class="notification-icon bg-primary">
                    <i class="fas fa-bell"></i>
                </div>
                @endif
                <div class="notification-content">
                    <span class="notification-title">{{ $notif->title }} <span style="font-weight: normal;">{{ $notif->message }}</span></span>
                    <span class="notification-time">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                </div>
            </div>
        </a>
    </li>
    @empty
    <li>
        <div class="text-center text-muted fw-bold">{{ __('No Notifications') }}</div>
    </li>
    @endforelse
    <li class="divider"></li>
    <li>
        @if (auth()->check() && auth()->user()->role == 'instructor')
        <a href="{{ route('instructor.notifications') }}" class="text-center view-all-notifications">
        @else
        <a href="{{ route('student.notifications') }}" class="text-center view-all-notifications">
        @endif
            {{ __('View All Notifications') }}
        </a>
    </li>
</ul>
